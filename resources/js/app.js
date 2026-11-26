import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // 0. GSAP Hero Tinder Couple Card Stack + Background Multi-Couple Slider
    const tinderDeck = document.querySelector('[data-tinder-deck]');
    const bgSlider = document.querySelector('[data-hero-bg-slider]');
    const prefersReducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

    if (tinderDeck) {
        const cardContainer = tinderDeck.querySelector('.tinder-deck-cards');
        const cards = Array.from(tinderDeck.querySelectorAll('[data-tinder-card]'));
        const bgSlides = bgSlider ? Array.from(bgSlider.querySelectorAll('[data-bg-index]')) : [];
        const counterEl = tinderDeck.querySelector('[data-deck-current]');
        const prevBtn = tinderDeck.querySelector('[data-deck-prev]');
        const nextBtn = tinderDeck.querySelector('[data-deck-next]');

        let order = cards.map((_, i) => i);
        let isAnimating = false;
        let autoTimer = null;

        if (!prefersReducedMotion) {
            gsap.from('.hero-copy > *', {
                y: 12,
                opacity: 0,
                duration: 0.45,
                stagger: 0.06,
                ease: 'power2.out',
                clearProps: 'transform,opacity',
            });
        }

        const syncBgSlide = (activeIdx) => {
            if (!bgSlides.length) return;
            bgSlides.forEach((slide, i) => {
                const isTarget = i === activeIdx;
                slide.classList.toggle('is-active', isTarget);
                if (prefersReducedMotion) {
                    slide.style.opacity = isTarget ? '1' : '0';
                    return;
                }
                const img = slide.querySelector('img');
                gsap.to(slide, {
                    opacity: isTarget ? 1 : 0,
                    duration: 0.85,
                    ease: 'power2.inOut',
                });
                if (isTarget && img) {
                    gsap.fromTo(
                        img,
                        { scale: 1.05 },
                        { scale: 1, duration: 5.2, ease: 'power1.out', overwrite: 'auto' }
                    );
                }
            });
            if (counterEl) {
                counterEl.textContent = String(activeIdx + 1);
            }
        };

        const layoutDeck = (immediate = false) => {
            const total = order.length;
            order.forEach((cardIdx, stackPos) => {
                const el = cards[cardIdx];
                if (!el) return;
                const depth = Math.min(stackPos, 3);
                const xOffset = stackPos === 0 ? 0 : (stackPos % 2 === 1 ? 10 * depth : -8 * depth);
                const yOffset = depth * 11;
                const scale = 1 - depth * 0.045;
                const rot = stackPos === 0 ? 0 : (stackPos % 2 === 1 ? 2.2 * depth : -2.2 * depth);
                const opacity = stackPos > 3 ? 0 : 1 - depth * 0.1;

                el.style.zIndex = String(total - stackPos);
                el.style.pointerEvents = stackPos === 0 ? 'auto' : 'none';

                const likeStamp = el.querySelector('.tinder-stamp-like');
                const passStamp = el.querySelector('.tinder-stamp-pass');
                if (likeStamp) likeStamp.style.opacity = '0';
                if (passStamp) passStamp.style.opacity = '0';

                if (immediate || prefersReducedMotion) {
                    gsap.set(el, { x: xOffset, y: yOffset, scale, rotation: rot, opacity });
                } else {
                    gsap.to(el, {
                        x: xOffset,
                        y: yOffset,
                        scale,
                        rotation: rot,
                        opacity,
                        duration: 0.42,
                        ease: 'power3.out',
                        overwrite: 'auto',
                    });
                }
            });
            syncBgSlide(order[0]);
        };

        const cycleCard = (direction = 1) => {
            if (isAnimating || order.length < 2) return;
            isAnimating = true;
            const topCard = cards[order[0]];

            const onThrown = () => {
                if (direction > 0) {
                    order.push(order.shift());
                } else {
                    order.unshift(order.pop());
                }
                layoutDeck(false);
                isAnimating = false;
            };

            if (prefersReducedMotion) {
                onThrown();
                return;
            }

            const targetX = direction > 0 ? 340 : -340;
            const targetRot = direction > 0 ? 15 : -15;
            const activeStamp = topCard.querySelector(direction > 0 ? '.tinder-stamp-like' : '.tinder-stamp-pass');
            if (activeStamp) activeStamp.style.opacity = '1';

            gsap.to(topCard, {
                x: targetX,
                y: 16,
                rotation: targetRot,
                opacity: 0,
                duration: 0.36,
                ease: 'power2.in',
                onComplete: () => {
                    gsap.set(topCard, { x: 0, y: 28, rotation: 0, scale: 0.88, opacity: 0 });
                    onThrown();
                },
            });
        };

        const startAuto = () => {
            clearInterval(autoTimer);
            autoTimer = setInterval(() => cycleCard(1), 4600);
        };

        const stopAuto = () => clearInterval(autoTimer);

        // Pointer drag swipe like Tinder
        let dragging = false;
        let startX = 0;
        let currentDx = 0;
        let activeTop = null;

        cardContainer?.addEventListener('pointerdown', (e) => {
            if (isAnimating) return;
            const top = cards[order[0]];
            if (!top || e.target.closest('button')) return;
            dragging = true;
            activeTop = top;
            startX = e.clientX;
            currentDx = 0;
            stopAuto();
            top.setPointerCapture?.(e.pointerId);
        });

        cardContainer?.addEventListener('pointermove', (e) => {
            if (!dragging || !activeTop) return;
            currentDx = e.clientX - startX;
            const rot = Math.max(-14, Math.min(14, currentDx * 0.06));
            gsap.set(activeTop, { x: currentDx, rotation: rot });

            const likeStamp = activeTop.querySelector('.tinder-stamp-like');
            const passStamp = activeTop.querySelector('.tinder-stamp-pass');
            const ratio = Math.min(1, Math.abs(currentDx) / 90);
            if (likeStamp) likeStamp.style.opacity = currentDx > 12 ? String(ratio) : '0';
            if (passStamp) passStamp.style.opacity = currentDx < -12 ? String(ratio) : '0';
        });

        const endDrag = () => {
            if (!dragging || !activeTop) return;
            const dx = currentDx;
            const card = activeTop;
            dragging = false;
            activeTop = null;
            currentDx = 0;

            if (Math.abs(dx) >= 85) {
                cycleCard(dx > 0 ? 1 : -1);
            } else {
                const likeStamp = card.querySelector('.tinder-stamp-like');
                const passStamp = card.querySelector('.tinder-stamp-pass');
                if (likeStamp) likeStamp.style.opacity = '0';
                if (passStamp) passStamp.style.opacity = '0';
                gsap.to(card, { x: 0, rotation: 0, duration: 0.35, ease: 'power3.out' });
            }
            startAuto();
        };

        cardContainer?.addEventListener('pointerup', endDrag);
        cardContainer?.addEventListener('pointercancel', endDrag);

        nextBtn?.addEventListener('click', () => {
            stopAuto();
            cycleCard(1);
            startAuto();
        });
        prevBtn?.addEventListener('click', () => {
            stopAuto();
            cycleCard(-1);
            startAuto();
        });

        tinderDeck.addEventListener('mouseenter', stopAuto);
        tinderDeck.addEventListener('mouseleave', startAuto);

        layoutDeck(true);
        startAuto();
    }

    // 1. Fast navigation progress indicator
    const progressBar = document.getElementById('cupPageProgress');
    document.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href') || '';
            if (href.startsWith('#') || link.target === '_blank' || e.metaKey || e.ctrlKey) return;
            if (progressBar) progressBar.classList.add('is-active');
        });
    });

    // 2. Fast skeleton loader reveal + per-image fade-in
    document.querySelectorAll('[data-skeleton-container]').forEach((wrap) => {
        const loader = wrap.querySelector('[data-skeleton-loader]');
        if (!loader) return;
        requestAnimationFrame(() => {
            setTimeout(() => loader.classList.add('is-hidden'), 180);
        });
    });

    // Interactive Truth or Dare Card Game with GSAP Reveal
    const tdApp = document.querySelector('[data-truth-dare-app]');
    if (tdApp) {
        const tdDeck = [
            { type: 'TRUTH', cat: 'coffee', text: 'What is one green flag you notice within the first five minutes of a coffee date?' },
            { type: 'TRUTH', cat: 'coffee', text: 'Which cafe in your city has the best atmosphere for a calm first conversation?' },
            { type: 'TRUTH', cat: 'couple', text: 'What small daily habit makes you feel most appreciated by a partner?' },
            { type: 'TRUTH', cat: 'couple', text: 'Would you prefer a sunrise mountain road trip or a rainy bookstore afternoon together?' },
            { type: 'TRUTH', cat: 'friends', text: 'What trip across India surprised you the most and why?' },
            { type: 'TRUTH', cat: 'friends', text: 'If your group had 48 hours and a full fuel tank this weekend, where would you drive?' },
            { type: 'TRUTH', cat: 'video', text: 'What song or playlist always improves your mood during a busy week?' },
            { type: 'TRUTH', cat: 'video', text: 'What hobby are you genuinely curious to learn this year?' },
            { type: 'DARE', cat: 'coffee', text: 'Order or recommend the next drink/pastry for the other person based only on their personality.' },
            { type: 'DARE', cat: 'coffee', text: 'Share the most candid photo from your last coffee walk or mountain viewpoint.' },
            { type: 'DARE', cat: 'couple', text: 'Give two sincere, specific compliments about how they handle conversations.' },
            { type: 'DARE', cat: 'couple', text: 'Plan an imaginary Sunday morning itinerary in under 30 seconds.' },
            { type: 'DARE', cat: 'friends', text: 'Describe your dream weekend travel route using only three city names and one food dish.' },
            { type: 'DARE', cat: 'friends', text: 'Share the funniest minor travel detour that turned into a great memory.' },
            { type: 'DARE', cat: 'video', text: 'Show the mug, notebook, or view beside you right now on camera and tell its short story.' },
            { type: 'DARE', cat: 'video', text: 'Ask one unexpected icebreaker question before the timer hits 60 seconds.' },
        ];

        let activeCat = 'all';
        const cardEl = document.getElementById('tdPromptCard');
        const modeEl = document.getElementById('tdModeLabel');
        const textEl = document.getElementById('tdPromptText');
        const hintEl = document.getElementById('tdCategoryHint');
        const copyBtn = document.getElementById('tdCopyBtn');

        tdApp.querySelectorAll('[data-td-category]').forEach((btn) => {
            btn.addEventListener('click', () => {
                tdApp.querySelectorAll('[data-td-category]').forEach((b) => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                activeCat = btn.getAttribute('data-td-category') || 'all';
            });
        });

        const drawCard = (wantedType = null) => {
            const pool = tdDeck.filter((item) => {
                const catOk = activeCat === 'all' || item.cat === activeCat;
                const typeOk = !wantedType || item.type === wantedType;
                return catOk && typeOk;
            });
            const pick = pool[Math.floor(Math.random() * pool.length)] || tdDeck[0];
            if (modeEl) modeEl.textContent = pick.type;
            if (textEl) textEl.textContent = pick.text;
            if (hintEl) hintEl.textContent = `Category: ${pick.cat.toUpperCase()} - Respectful CupDate Deck`;
            if (!prefersReducedMotion && cardEl) {
                gsap.fromTo(cardEl, { scale: 0.96, y: 8, opacity: 0.65 }, { scale: 1, y: 0, opacity: 1, duration: 0.32, ease: 'power2.out' });
            }
        };

        document.getElementById('tdTruthBtn')?.addEventListener('click', () => drawCard('TRUTH'));
        document.getElementById('tdDareBtn')?.addEventListener('click', () => drawCard('DARE'));
        document.getElementById('tdRandomBtn')?.addEventListener('click', () => drawCard(null));
        copyBtn?.addEventListener('click', async () => {
            const content = `${modeEl?.textContent}: ${textEl?.textContent}`;
            try {
                await navigator.clipboard.writeText(content);
                copyBtn.textContent = 'Copied';
                setTimeout(() => { copyBtn.textContent = 'Copy Prompt'; }, 1600);
            } catch {
                copyBtn.textContent = 'Copy Prompt';
            }
        });
    }

    document.querySelectorAll('.profile-card-img-wrap img, .moment-img-wrap img').forEach((img) => {
        if (img.complete) {
            img.classList.add('is-loaded');
        } else {
            img.addEventListener('load', () => img.classList.add('is-loaded'), { once: true });
            img.addEventListener('error', () => img.classList.add('is-loaded'), { once: true });
        }
    });

    // Mobile messages back-to-threads toggle
    const msgShell = document.getElementById('messagesShell');
    const backToThreads = document.getElementById('mobileBackToThreads');
    if (msgShell && backToThreads) {
        backToThreads.addEventListener('click', () => {
            msgShell.classList.remove('has-active-chat');
        });
    }

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

    // 5. Discover AJAX Like / Pass / Send Request persisted safely
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
                    btn.textContent = action === 'dislike' ? 'Passed' : (data.matched ? 'Matched' : 'Requested');
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
        const seenMsgIds = new Set(
            Array.from(chatFeed.querySelectorAll('[data-msg-id]')).map((el) => Number(el.getAttribute('data-msg-id')))
        );

        const appendMessage = (m) => {
            const msgId = Number(m.id || 0);
            if (msgId > 0 && seenMsgIds.has(msgId)) return;
            if (msgId > 0) seenMsgIds.add(msgId);

            document.getElementById('chatEmptyNote')?.remove();
            const wrap = document.createElement('div');
            wrap.className = `chat-msg ${m.is_me ? 'is-me' : 'is-them'}`;
            if (msgId > 0) wrap.setAttribute('data-msg-id', String(msgId));
            const bubble = document.createElement('div');
            bubble.className = 'chat-msg-bubble';
            if (m.image_url) {
                const img = document.createElement('img');
                img.src = m.image_url;
                img.className = 'chat-shared-img js-chat-zoom';
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
            time.textContent = `${m.time || 'Just now'}${m.is_me ? ' · Sent' : ''}`;
            wrap.append(bubble, time);
            chatFeed.appendChild(wrap);
            chatFeed.scrollTop = chatFeed.scrollHeight;
        };

        const chatImageInput = document.getElementById('chatImageInput');
        const previewBar = document.getElementById('chatImagePreviewBar');
        const previewImg = document.getElementById('chatImagePreviewEl');
        const previewName = document.getElementById('chatImageFileName');
        const clearImgBtn = document.getElementById('clearChatImageBtn');
        const errBanner = document.getElementById('chatErrorBanner');
        const sendBtn = document.getElementById('chatSendBtn');
        const chatInput = document.getElementById('chatTextInput');

        chatInput?.addEventListener('focus', () => {
            setTimeout(() => { chatFeed.scrollTop = chatFeed.scrollHeight; }, 160);
        });

        const showChatError = (msg) => {
            if (!errBanner) return;
            errBanner.textContent = msg;
            errBanner.hidden = false;
            setTimeout(() => { errBanner.hidden = true; }, 4500);
        };

        chatImageInput?.addEventListener('change', () => {
            const file = chatImageInput.files?.[0];
            if (!file) {
                if (previewBar) previewBar.hidden = true;
                return;
            }
            if (previewImg) previewImg.src = URL.createObjectURL(file);
            if (previewName) previewName.textContent = file.name;
            if (previewBar) previewBar.hidden = false;
        });

        clearImgBtn?.addEventListener('click', () => {
            if (chatImageInput) chatImageInput.value = '';
            if (previewBar) previewBar.hidden = true;
        });

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = (chatInput?.value || '').trim();
            const hasFile = chatImageInput && chatImageInput.files.length > 0;
            if (!text && !hasFile) return;

            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.textContent = 'Sending...';
            }
            try {
                let res;
                if (hasFile) {
                    const fd = new FormData();
                    if (text) fd.append('message', text);
                    fd.append('image', chatImageInput.files[0]);
                    res = await fetch(chatForm.action, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: fd,
                    });
                } else {
                    res = await fetch(chatForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({ message: text }),
                    });
                }
                const data = await res.json();
                if (res.ok && data.ok) {
                    if (chatInput) chatInput.value = '';
                    if (chatImageInput) chatImageInput.value = '';
                    if (previewBar) previewBar.hidden = true;
                    lastId = Math.max(lastId, Number(data.message.id));
                    chatFeed.setAttribute('data-last-id', String(lastId));
                    appendMessage({ ...data.message, is_me: true });
                } else if (data.error) {
                    showChatError(data.error);
                } else if (data.errors) {
                    const firstErr = Object.values(data.errors).flat()[0];
                    if (firstErr) showChatError(String(firstErr));
                }
            } catch {
                showChatError('Could not send message. Please check your connection.');
            } finally {
                if (sendBtn) {
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Send';
                }
            }
        });

        // Photo lightbox for shared images
        const lightbox = document.getElementById('chatPhotoLightbox');
        const lightboxImg = document.getElementById('chatLightboxImg');
        chatFeed.addEventListener('click', (e) => {
            const target = e.target;
            if (target instanceof HTMLImageElement && target.classList.contains('js-chat-zoom') && lightbox && lightboxImg) {
                lightboxImg.src = target.src;
                lightbox.hidden = false;
            }
        });
        document.getElementById('closeChatLightbox')?.addEventListener('click', () => { if (lightbox) lightbox.hidden = true; });
        lightbox?.addEventListener('click', (e) => { if (e.target === lightbox) lightbox.hidden = true; });

        setInterval(async () => {
            if (!pollUrl) return;
            try {
                const res = await fetch(`${pollUrl}?after=${lastId}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                const statusEl = document.getElementById('partnerOnlineStatus');
                if (statusEl && data.partner_status) statusEl.textContent = data.partner_status;
                (data.messages || []).forEach((m) => {
                    lastId = Math.max(lastId, Number(m.id));
                    if (!m.is_me) appendMessage(m);
                });
            } catch {
                // ignore network hiccup
            }
        }, 3000);
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
            statusText.textContent = 'Incoming video call waiting - click Accept Incoming';
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

    // 9. Fast link hover prefetch for instant navigation
    const prefetched = new Set();
    document.querySelectorAll('a[href^="/"]').forEach((a) => {
        a.addEventListener('mouseenter', () => {
            const href = a.getAttribute('href');
            if (!href || prefetched.has(href) || href.startsWith('/logout')) return;
            prefetched.add(href);
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = href;
            document.head.appendChild(link);
        }, { passive: true, once: true });
    });

    // 10. Real-time live visitor & member counts (real counts only)
    const liveStatsUrl = document.body.getAttribute('data-live-stats-url');
    if (liveStatsUrl) {
        const updateLiveStats = async () => {
            try {
                const res = await fetch(liveStatsUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                document.querySelectorAll('[data-live-visitors]').forEach((el) => {
                    el.textContent = String(data.active_visitors ?? 0);
                });
                document.querySelectorAll('[data-live-members]').forEach((el) => {
                    el.textContent = String(data.online_members ?? 0);
                });
                const qEl = document.getElementById('liveQueueCount');
                if (qEl) qEl.textContent = String(data.live_video_active ?? 0);
            } catch {
                // ignore transient network error
            }
        };
        setInterval(updateLiveStats, 15000);
    }

    // 11. Featured Profile Preview Modal
    const modal = document.getElementById('profilePreviewModal');
    if (modal) {
        const closeBtn = document.getElementById('closeProfileModal');
        document.querySelectorAll('.js-open-profile-modal').forEach((btn) => {
            btn.addEventListener('click', () => {
                const card = btn.closest('.js-profile-preview');
                if (!card) return;
                document.getElementById('modalProfileImg').src = card.dataset.avatar || '';
                document.getElementById('modalProfileName').textContent = card.dataset.name || 'Member';
                document.getElementById('modalProfileCity').textContent = card.dataset.city || '';
                document.getElementById('modalProfileBio').textContent = card.dataset.bio || '';
                document.getElementById('modalProfileCoffee').textContent = card.dataset.coffee || 'Cappuccino';
                document.getElementById('modalProfileBadge').textContent = card.dataset.verified || 'Member';
                modal.hidden = false;
            });
        });
        closeBtn?.addEventListener('click', () => { modal.hidden = true; });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.hidden = true;
        });
    }

    // 12. 100+ Cities SEO Directory Live Search
    const citySearch = document.getElementById('cityDirectorySearch');
    if (citySearch) {
        citySearch.addEventListener('input', () => {
            const q = citySearch.value.trim().toLowerCase();
            document.querySelectorAll('[data-city-group]').forEach((group) => {
                let visible = 0;
                group.querySelectorAll('[data-city-item]').forEach((item) => {
                    const match = !q || (item.dataset.cityItem || '').includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visible += 1;
                });
                group.style.display = visible > 0 ? '' : 'none';
            });
        });
    }

    // 13. Messages Thread Filter & Icebreakers
    const threadSearch = document.getElementById('threadsSearchInput');
    if (threadSearch) {
        threadSearch.addEventListener('input', () => {
            const q = threadSearch.value.trim().toLowerCase();
            document.querySelectorAll('[data-thread-name]').forEach((row) => {
                const match = !q || (row.dataset.threadName || '').includes(q);
                row.style.display = match ? '' : 'none';
            });
        });
    }
    document.querySelectorAll('.js-icebreaker').forEach((chip) => {
        chip.addEventListener('click', () => {
            const input = document.getElementById('chatTextInput');
            if (input) {
                input.value = chip.dataset.msg || '';
                input.focus();
            }
        });
    });

    // 14. Coffee Moments Social Media Share & Tracking
    const trackShare = (bar) => {
        const endpoint = bar?.getAttribute('data-share-endpoint');
        if (!endpoint) return;
        fetch(endpoint, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } }).catch(() => {});
    };
    document.querySelectorAll('.js-share-track').forEach((a) => {
        a.addEventListener('click', () => trackShare(a.closest('[data-share-endpoint]')));
    });
    document.querySelectorAll('.js-native-share').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const title = btn.dataset.title || 'CupDate Moment';
            const text = btn.dataset.text || '';
            const url = btn.dataset.url || window.location.href;
            trackShare(btn.closest('[data-share-endpoint]'));
            if (navigator.share) {
                try {
                    await navigator.share({ title, text, url });
                    return;
                } catch {
                    // fallback to clipboard
                }
            }
            if (navigator.clipboard) {
                await navigator.clipboard.writeText(url);
                const orig = btn.textContent;
                btn.textContent = 'Link Copied';
                setTimeout(() => { btn.textContent = orig; }, 1800);
            }
        });
    });

    // 15. OmeTV-Style Random Live Video Match
    const omeApp = document.getElementById('ometvApp');
    if (omeApp) {
        const startBtn = document.getElementById('omeStartBtn');
        const nextBtn = document.getElementById('omeNextBtn');
        const muteBtn = document.getElementById('omeMuteBtn');
        const camBtn = document.getElementById('omeCamBtn');
        const stopBtn = document.getElementById('omeStopBtn');
        const reportBtn = document.getElementById('omeReportBtn');
        const partnerLabel = document.getElementById('omePartnerLabel');
        const statusSub = document.getElementById('omeStatusSub');
        const partnerBadge = document.getElementById('omePartnerBadge');
        const remoteOverlay = document.getElementById('omeRemoteOverlay');
        const localOverlay = document.getElementById('omeLocalOverlay');
        const localVideo = document.getElementById('omeLocalVideo');
        const remoteVideo = document.getElementById('omeRemoteVideo');
        const omeChatFeed = document.getElementById('omeChatFeed');
        const omeChatForm = document.getElementById('omeChatForm');
        const omeChatInput = document.getElementById('omeChatInput');
        const omeChatSend = document.getElementById('omeChatSend');

        let omeStream = null;
        let omePc = null;
        let queueId = null;
        let roomId = null;
        let lastSignal = 0;
        let lastMsg = 0;
        let pollTimer = null;

        const appendOmeMsg = (sender, text, isMe = false) => {
            if (!omeChatFeed) return;
            const row = document.createElement('div');
            row.className = `ome-chat-line ${isMe ? 'is-me' : ''}`;
            const strong = document.createElement('strong');
            strong.textContent = `${sender}: `;
            const span = document.createElement('span');
            span.textContent = text;
            row.append(strong, span);
            omeChatFeed.appendChild(row);
            omeChatFeed.scrollTop = omeChatFeed.scrollHeight;
        };

        const ensureCamera = async () => {
            if (!omeStream) {
                omeStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                localVideo.srcObject = omeStream;
                if (localOverlay) localOverlay.style.display = 'none';
            }
            muteBtn.disabled = false;
            camBtn.disabled = false;
            stopBtn.disabled = false;
        };

        const resetPeer = () => {
            if (omePc) {
                omePc.close();
                omePc = null;
            }
            remoteVideo.srcObject = null;
            if (remoteOverlay) remoteOverlay.style.display = 'flex';
            if (partnerBadge) partnerBadge.hidden = true;
            roomId = null;
            lastSignal = 0;
            lastMsg = 0;
            omeChatInput.disabled = true;
            omeChatSend.disabled = true;
            reportBtn.disabled = true;
        };

        const initPeer = async (match) => {
            roomId = match.room_id;
            partnerLabel.textContent = `Connected with ${match.partner_name} (${match.partner_country})`;
            statusSub.textContent = 'Encrypted live video active - stay respectful';
            if (partnerBadge) {
                partnerBadge.textContent = `${match.partner_name} · Live`;
                partnerBadge.hidden = false;
            }
            omeChatFeed.innerHTML = '';
            omeChatInput.disabled = false;
            omeChatSend.disabled = false;
            reportBtn.disabled = false;

            omePc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });
            omeStream.getTracks().forEach((t) => omePc.addTrack(t, omeStream));
            omePc.ontrack = (ev) => {
                remoteVideo.srcObject = ev.streams[0];
                if (remoteOverlay) remoteOverlay.style.display = 'none';
            };
            omePc.onicecandidate = async (ev) => {
                if (ev.candidate && queueId) {
                    await fetch(omeApp.dataset.signalUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ queue_id: queueId, type: 'ice', payload: JSON.stringify(ev.candidate) }),
                    });
                }
            };

            if (match.initiator) {
                const offer = await omePc.createOffer();
                await omePc.setLocalDescription(offer);
                await fetch(omeApp.dataset.signalUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ queue_id: queueId, type: 'offer', payload: JSON.stringify(offer) }),
                });
            }
        };

        const joinQueue = async () => {
            clearInterval(pollTimer);
            resetPeer();
            partnerLabel.textContent = 'Finding an online match...';
            statusSub.textContent = 'Matching you with a safe, verified peer';
            nextBtn.disabled = false;

            const res = await fetch(omeApp.dataset.joinUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    target_gender: document.getElementById('omeTargetGender')?.value || 'everyone',
                    country: document.getElementById('omeCountry')?.value || 'all',
                }),
            });
            const data = await res.json();
            if (!res.ok || !data.ok) return;
            queueId = data.queue_id;
            const qEl = document.getElementById('liveQueueCount');
            if (qEl) qEl.textContent = String(data.waiting_count ?? 0);

            if (data.match) {
                await initPeer(data.match);
            }

            pollTimer = setInterval(async () => {
                if (!queueId) return;
                const pRes = await fetch(`${omeApp.dataset.pollUrl}?queue_id=${queueId}&after_signal=${lastSignal}&after_msg=${lastMsg}`);
                if (!pRes.ok) return;
                const pData = await pRes.json();
                if (qEl && pData.waiting_count !== undefined) qEl.textContent = String(pData.waiting_count);

                if (pData.ended) {
                    partnerLabel.textContent = 'Partner left. Finding next match...';
                    joinQueue();
                    return;
                }
                if (pData.match && !roomId) {
                    await initPeer(pData.match);
                }
                for (const sig of pData.signals || []) {
                    lastSignal = Math.max(lastSignal, Number(sig.id));
                    const payload = JSON.parse(sig.payload);
                    if (sig.type === 'offer' && omePc) {
                        await omePc.setRemoteDescription(new RTCSessionDescription(payload));
                        const answer = await omePc.createAnswer();
                        await omePc.setLocalDescription(answer);
                        await fetch(omeApp.dataset.signalUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: JSON.stringify({ queue_id: queueId, type: 'answer', payload: JSON.stringify(answer) }),
                        });
                    } else if (sig.type === 'answer' && omePc) {
                        await omePc.setRemoteDescription(new RTCSessionDescription(payload));
                    } else if (sig.type === 'ice' && omePc) {
                        await omePc.addIceCandidate(new RTCIceCandidate(payload));
                    }
                }
                for (const msg of pData.messages || []) {
                    lastMsg = Math.max(lastMsg, Number(msg.id));
                    appendOmeMsg(msg.sender_name, msg.text, Number(msg.sender_queue_id) === Number(queueId));
                }
            }, 1500);
        };

        startBtn?.addEventListener('click', async () => {
            try {
                await ensureCamera();
                startBtn.disabled = true;
                await joinQueue();
            } catch {
                partnerLabel.textContent = 'Camera & microphone permission required for Live Video.';
            }
        });

        nextBtn?.addEventListener('click', () => joinQueue());

        stopBtn?.addEventListener('click', async () => {
            clearInterval(pollTimer);
            resetPeer();
            if (queueId) {
                await fetch(omeApp.dataset.leaveUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ queue_id: queueId }),
                });
                queueId = null;
            }
            omeStream?.getTracks().forEach((t) => t.stop());
            omeStream = null;
            if (localOverlay) localOverlay.style.display = 'flex';
            startBtn.disabled = false;
            nextBtn.disabled = true;
            muteBtn.disabled = true;
            camBtn.disabled = true;
            stopBtn.disabled = true;
            partnerLabel.textContent = 'Click "Start Live Match" to connect';
        });

        muteBtn?.addEventListener('click', () => {
            const track = omeStream?.getAudioTracks()[0];
            if (track) {
                track.enabled = !track.enabled;
                muteBtn.textContent = track.enabled ? 'Mute' : 'Unmute';
            }
        });

        camBtn?.addEventListener('click', () => {
            const track = omeStream?.getVideoTracks()[0];
            if (track) {
                track.enabled = !track.enabled;
                camBtn.textContent = track.enabled ? 'Cam Off' : 'Cam On';
            }
        });

        reportBtn?.addEventListener('click', async () => {
            if (!queueId) return;
            await fetch(omeApp.dataset.reportUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ queue_id: queueId, reason: 'Inappropriate video/spam' }),
            });
            joinQueue();
        });

        omeChatForm?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = omeChatInput.value.trim();
            if (!text || !queueId) return;
            omeChatInput.value = '';
            const res = await fetch(omeApp.dataset.chatUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ queue_id: queueId, message: text }),
            });
            const data = await res.json();
            if (!res.ok && data.error) {
                appendOmeMsg('Safety Guard', data.error, false);
            }
        });

        window.addEventListener('beforeunload', () => {
            if (queueId && navigator.sendBeacon) {
                const fd = new FormData();
                fd.append('queue_id', String(queueId));
                fd.append('_token', csrf);
                navigator.sendBeacon(omeApp.dataset.leaveUrl, fd);
            }
        });
    }
});
