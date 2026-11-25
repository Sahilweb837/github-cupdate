document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // 1. Fast navigation progress indicator
    const progressBar = document.getElementById('cupPageProgress');
    document.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href') || '';
            if (href.startsWith('#') || link.target === '_blank' || e.metaKey || e.ctrlKey) return;
            if (progressBar) progressBar.classList.add('is-active');
        });
    });

    // 2. Cute skeleton loader reveal
    document.querySelectorAll('[data-skeleton-container]').forEach((wrap) => {
        const loader = wrap.querySelector('[data-skeleton-loader]');
        if (!loader) return;
        setTimeout(() => loader.classList.add('is-hidden'), 220);
    });

    // 3. Mobile menu toggle
    const navToggle = document.getElementById('navToggle');
    const siteNav = document.getElementById('siteNav');
    if (navToggle && siteNav) {
        navToggle.addEventListener('click', () => {
            const open = siteNav.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', String(open));
        });
    }

    // 4. FAQ accordion
    document.querySelectorAll('[data-faq-accordion] .faq-question').forEach((btn) => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            const open = item.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', String(open));
        });
    });

    // 5. Discover AJAX Like / Pass / Send Request persisted in MySQL
    document.querySelectorAll('[data-swipe-btn]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const targetId = btn.getAttribute('data-target');
            const action = btn.getAttribute('data-action');
            const card = btn.closest('[data-discover-card]');
            btn.disabled = true;

            try {
                const res = await fetch('/app/swipe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ target_id: Number(targetId), action }),
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    btn.textContent = action === 'dislike' ? 'Passed' : (data.matched ? 'Matched ❤' : 'Requested ✓');
                    if (card && action === 'dislike') card.style.opacity = '0.55';
                } else {
                    btn.disabled = false;
                }
            } catch {
                btn.disabled = false;
            }
        });
    });

    // 6. Real-time 1-to-1 Chat Polling + AJAX Send
    const chatFeed = document.getElementById('liveChatFeed');
    const chatForm = document.getElementById('liveChatForm');
    if (chatFeed && chatForm) {
        chatFeed.scrollTop = chatFeed.scrollHeight;
        let lastId = Number(chatFeed.getAttribute('data-last-id') || 0);
        const pollUrl = chatFeed.getAttribute('data-poll-url');

        const appendMessage = (m) => {
            const wrap = document.createElement('div');
            wrap.className = `chat-msg ${m.is_me ? 'is-me' : 'is-them'}`;
            const bubble = document.createElement('div');
            bubble.className = 'chat-msg-bubble';
            if (m.image_url) {
                const img = document.createElement('img');
                img.src = m.image_url;
                img.className = 'chat-shared-img';
                img.alt = 'Shared photo';
                bubble.appendChild(img);
            }
            if (m.body) {
                const p = document.createElement('p');
                p.textContent = m.body;
                bubble.appendChild(p);
            }
            const time = document.createElement('span');
            time.className = 'chat-msg-time';
            time.textContent = m.time || 'Just now';
            wrap.append(bubble, time);
            chatFeed.appendChild(wrap);
            chatFeed.scrollTop = chatFeed.scrollHeight;
        };

        chatForm.addEventListener('submit', async (e) => {
            const fileInput = chatForm.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) return; // normal multipart submit for images
            e.preventDefault();
            const input = document.getElementById('chatTextInput');
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            try {
                const res = await fetch(chatForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ message: text }),
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    lastId = Math.max(lastId, Number(data.message.id));
                    appendMessage({ ...data.message, is_me: true });
                }
            } catch {
                // fallback
            }
        });

        setInterval(async () => {
            if (!pollUrl) return;
            try {
                const res = await fetch(`${pollUrl}?after=${lastId}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                (data.messages || []).forEach((m) => {
                    lastId = Math.max(lastId, Number(m.id));
                    if (!m.is_me) appendMessage(m);
                });
            } catch {
                // ignore network hiccup
            }
        }, 3500);
    }

    // 7. Incoming Call Toast across App Pages
    const incomingToast = document.getElementById('incomingCallToast');
    const incomingUrl = '/app/video-call/incoming';
    if (incomingToast) {
        setInterval(async () => {
            try {
                const res = await fetch(incomingUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                if (data.call) {
                    document.getElementById('incomingCallerName').textContent = `Incoming call from ${data.call.caller?.full_name || 'Member'}`;
                    document.getElementById('incomingJoinLink').href = `/app/video-call?with=${data.call.caller_id}&call=${data.call.id}`;
                    incomingToast.hidden = false;
                } else {
                    incomingToast.hidden = true;
                }
            } catch {
                // silent
            }
        }, 5000);
    }

    // 8. WebRTC 1-on-1 Video Calling
    const videoApp = document.querySelector('[data-webrtc-app]');
    if (videoApp) {
        const startBtn = document.getElementById('startCallBtn');
        const acceptBtn = document.getElementById('acceptCallBtn');
        const muteBtn = document.getElementById('toggleMuteBtn');
        const camBtn = document.getElementById('toggleCamBtn');
        const endBtn = document.getElementById('endCallBtn');
        const partnerSelect = document.getElementById('callPartnerSelect');
        const statusText = document.getElementById('videoStatusText');
        const placeholder = document.getElementById('videoPlaceholder');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');
        const timerEl = document.getElementById('videoCallTimer');

        let localStream = null;
        let pc = null;
        let activeCallId = new URLSearchParams(window.location.search).get('call');
        let lastSignalId = 0;
        let pollTimer = null;
        let clockTimer = null;
        let seconds = 0;

        const startMedia = async () => {
            if (!localStream) {
                localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                localVideo.srcObject = localStream;
            }
            placeholder.style.display = 'none';
            muteBtn.disabled = false;
            camBtn.disabled = false;
            endBtn.disabled = false;
        };

        const createPeer = (callId) => {
            pc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });
            localStream.getTracks().forEach((track) => pc.addTrack(track, localStream));
            pc.ontrack = (ev) => { remoteVideo.srcObject = ev.streams[0]; };
            pc.onicecandidate = async (ev) => {
                if (ev.candidate) {
                    await fetch(`/app/video-call/${callId}/signal`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ type: 'ice', payload: JSON.stringify(ev.candidate) }),
                    });
                }
            };
        };

        const startTimer = () => {
            seconds = 0;
            clearInterval(clockTimer);
            clockTimer = setInterval(() => {
                seconds += 1;
                const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                timerEl.textContent = `${mins}:${secs}`;
            }, 1000);
        };

        const beginSignalPolling = (callId) => {
            clearInterval(pollTimer);
            pollTimer = setInterval(async () => {
                const res = await fetch(`/app/video-call/${callId}/signals?after=${lastSignalId}`);
                if (!res.ok) return;
                const data = await res.json();
                for (const sig of data.signals || []) {
                    lastSignalId = Math.max(lastSignalId, sig.id);
                    const payload = JSON.parse(sig.payload);
                    if (sig.type === 'offer') {
                        await pc.setRemoteDescription(new RTCSessionDescription(payload));
                        const answer = await pc.createAnswer();
                        await pc.setLocalDescription(answer);
                        await fetch(`/app/video-call/${callId}/signal`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: JSON.stringify({ type: 'answer', payload: JSON.stringify(answer) }),
                        });
                        startTimer();
                    } else if (sig.type === 'answer') {
                        await pc.setRemoteDescription(new RTCSessionDescription(payload));
                        startTimer();
                    } else if (sig.type === 'ice') {
                        await pc.addIceCandidate(new RTCIceCandidate(payload));
                    }
                }
            }, 2000);
        };

        if (activeCallId) {
            acceptBtn.hidden = false;
            statusText.textContent = 'Incoming video call waiting — click Accept Incoming';
        }

        startBtn?.addEventListener('click', async () => {
            const receiverId = partnerSelect.value;
            if (!receiverId) return alert('Choose a connection first.');
            try {
                await startMedia();
                const res = await fetch(videoApp.dataset.startUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ receiver_id: Number(receiverId) }),
                });
                const data = await res.json();
                activeCallId = data.call.id;
                createPeer(activeCallId);
                const offer = await pc.createOffer();
                await pc.setLocalDescription(offer);
                await fetch(`/app/video-call/${activeCallId}/signal`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ type: 'offer', payload: JSON.stringify(offer) }),
                });
                beginSignalPolling(activeCallId);
                startTimer();
            } catch {
                statusText.textContent = 'Allow camera & microphone permissions to place a video call.';
            }
        });

        acceptBtn?.addEventListener('click', async () => {
            if (!activeCallId) return;
            await startMedia();
            createPeer(activeCallId);
            await fetch(`/app/video-call/${activeCallId}/status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ status: 'accepted' }),
            });
            acceptBtn.hidden = true;
            beginSignalPolling(activeCallId);
            startTimer();
        });

        muteBtn?.addEventListener('click', () => {
            if (!localStream) return;
            const audioTrack = localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                muteBtn.textContent = audioTrack.enabled ? 'Mute Mic' : 'Unmute Mic';
            }
        });

        camBtn?.addEventListener('click', () => {
            if (!localStream) return;
            const videoTrack = localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = !videoTrack.enabled;
                camBtn.textContent = videoTrack.enabled ? 'Camera Off' : 'Camera On';
            }
        });

        endBtn?.addEventListener('click', async () => {
            clearInterval(pollTimer);
            clearInterval(clockTimer);
            localStream?.getTracks().forEach((t) => t.stop());
            pc?.close();
            if (activeCallId) {
                await fetch(`/app/video-call/${activeCallId}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ status: 'ended', duration_seconds: seconds }),
                });
            }
            window.location.href = '/app/video-call';
        });
    }
});
