<!-- AI Chat widget script -->
<script>
    $(function() {
        const chatToggle = $('#ai-chat-toggle');
        const chatWidget = $('#ai-chat-widget');
        const chatClose = $('#ai-chat-close');
        const chatMin = $('#ai-chat-minimize');
        const chatSend = $('#ai-chat-send');
        const chatInput = $('#ai-chat-input');
        const chatMessages = $('#ai-chat-messages');
        const chatModelToggle = $('#ai-chat-model-toggle');
        const chatModelList = $('#ai-chat-model-list');
        const chatModelOptions = $('#ai-chat-model-options');
        const chatModelStatus = $('#ai-chat-model-status');
        const chatThemeToggle = $('#ai-chat-theme-toggle');
        const chatThemePanel = $('#ai-chat-theme-panel');
        const chatThemeBg = $('#ai-chat-theme-bg');
        const chatThemeText = $('#ai-chat-theme-text');
        const chatThemeHeaderBg = $('#ai-chat-theme-header-bg');
        const chatThemeHeaderText = $('#ai-chat-theme-header-text');
        const chatThemeFont = $('#ai-chat-theme-font');
        const chatThemeReset = $('#ai-chat-theme-reset');

        $('#ai-chat-minimize')
            .attr({ type: 'button', 'aria-label': 'Minimize chat', title: 'Minimize' })
            .html('<i class="bx bx-minus"></i>');
        $('#ai-chat-expand')
            .attr({ type: 'button', 'aria-label': 'Expand chat', title: 'Expand' })
            .html('<i class="bx bx-expand-alt"></i>');
        $('#ai-chat-close')
            .attr({ type: 'button', 'aria-label': 'Close chat', title: 'Close' })
            .html('<i class="bx bx-x"></i>');
        $('#ai-chat-convos-close')
            .attr({ type: 'button', 'aria-label': 'Close conversations', title: 'Close' })
            .html('<i class="bx bx-x"></i>');
        $('#ai-chat-minimize, #ai-chat-expand, #ai-chat-close').parent().addClass('ai-chat-actions');

        let currentConversationId = null;
        let selectedModel = localStorage.getItem('ai_chat_model') || '';
        let modelsLoaded = false;
        let hasActiveConversationMessages = false;
        const chatPositionKey = 'ai_chat_pos_v2';
        const chatThemeKey = 'ai_chat_theme_v1';

        const savedConversationId = localStorage.getItem('ai_chat_conversation_id');
        if (savedConversationId) {
            currentConversationId = savedConversationId;
        }

        function positionWidgetAtDefault() {
            chatWidget.css({
                position: 'fixed',
                right: '24px',
                bottom: '24px',
                left: 'auto',
                top: 'auto'
            });
        }

        function loadTheme() {
            try {
                return JSON.parse(localStorage.getItem(chatThemeKey) || '{}');
            } catch (e) {
                return {};
            }
        }

        function saveTheme(theme) {
            localStorage.setItem(chatThemeKey, JSON.stringify(theme));
        }

        function applyTheme(theme) {
            if (!chatWidget.length) return;

            const el = chatWidget[0];
            if (theme.bg) {
                el.style.setProperty('--ai-chat-bg', theme.bg);
            } else {
                el.style.removeProperty('--ai-chat-bg');
            }

            if (theme.text) {
                el.style.setProperty('--ai-chat-text', theme.text);
            } else {
                el.style.removeProperty('--ai-chat-text');
            }

            if (theme.headerBg) {
                el.style.setProperty('--ai-chat-header-bg', theme.headerBg);
            } else {
                el.style.removeProperty('--ai-chat-header-bg');
            }

            if (theme.headerText) {
                el.style.setProperty('--ai-chat-header-text', theme.headerText);
            } else {
                el.style.removeProperty('--ai-chat-header-text');
            }

            if (theme.font) {
                el.style.setProperty('--ai-chat-font', theme.font);
            } else {
                el.style.removeProperty('--ai-chat-font');
            }
        }

        const defaultTheme = {
            bg: '#ffffff',
            text: '#1f2937',
            headerBg: '#ffffff',
            headerText: '#1f2937',
            font: 'Plus Jakarta Sans, Poppins, Nunito, sans-serif',
        };

        function updateThemeInputs(theme) {
            const merged = { ...defaultTheme, ...(theme || {}) };
            chatThemeBg.val(merged.bg);
            chatThemeText.val(merged.text);
            chatThemeHeaderBg.val(merged.headerBg);
            chatThemeHeaderText.val(merged.headerText);
            chatThemeFont.val(merged.font);
        }

        function clampWidgetPosition(left, top) {
            const padding = 12;
            const winW = $(window).width();
            const winH = $(window).height();
            const width = chatWidget.outerWidth() || 360;
            const height = chatWidget.outerHeight() || 420;

            return {
                left: Math.max(padding, Math.min(left, winW - width - padding)),
                top: Math.max(padding, Math.min(top, winH - height - padding))
            };
        }

        function applySavedWidgetPosition() {
            const posRaw = localStorage.getItem(chatPositionKey);
            if (!posRaw) {
                positionWidgetAtDefault();
                return;
            }

            try {
                const p = JSON.parse(posRaw);
                if (!p || typeof p.left !== 'number' || typeof p.top !== 'number') {
                    positionWidgetAtDefault();
                    return;
                }

                const next = clampWidgetPosition(p.left, p.top);
                chatWidget.css({
                    position: 'fixed',
                    left: next.left + 'px',
                    top: next.top + 'px',
                    right: 'auto',
                    bottom: 'auto'
                });
                localStorage.setItem(chatPositionKey, JSON.stringify({
                    left: Math.round(next.left),
                    top: Math.round(next.top)
                }));
            } catch (e) {
                localStorage.removeItem(chatPositionKey);
                positionWidgetAtDefault();
            }
        }

        function loadConversationHistory(conversationId = null) {
            // load conversation history and render
            const url = "{{ route('ai.history') }}" + (conversationId ? '?conversation_id=' + conversationId : '');
            $.ajax({
                url: url,
                method: 'GET',
                success(res) {
                    chatMessages.empty();
                    const msgs = res.messages || [];
                    hasActiveConversationMessages = msgs.length > 0;

                    if (res.conversation && res.conversation.id) {
                        currentConversationId = res.conversation.id;
                        const rawTitle = res.conversation.title || '';
                        const displayTitle = rawTitle && !rawTitle.startsWith('Conversation ')
                            ? truncateTitle(rawTitle)
                            : 'New conversation';
                        $('#ai-chat-title').text(displayTitle);
                        // persist selected conversation so reload keeps it
                        localStorage.setItem('ai_chat_conversation_id', String(currentConversationId));
                    }

                    if (!msgs || msgs.length === 0) {
                        chatMessages.html('<div class="text-muted small">Say hi to the AI assistant.</div>');
                        return;
                    }

                    // normalize and render messages defensively
                    msgs.forEach(raw => {
                        let m = null;
                        if (!raw) return;
                        if (raw.role && raw.content) {
                            m = raw;
                        } else if (Array.isArray(raw) && raw.length >= 2) {
                            m = { role: raw[0], content: raw[1] };
                        } else if (raw.message && raw.role) {
                            m = { role: raw.role, content: raw.message };
                        } else if (raw.text && raw.role) {
                            m = { role: raw.role, content: raw.text };
                        }

                        if (m) {
                            appendMessage(m.role === 'user' ? 'user' : 'assistant', m.content);
                        }
                    });
                },
                error(xhr, status, err) {
                    console.error('Failed loading conversation history', status, err, xhr.responseText);
                }
            });
        }

        function setSelectedModel(model) {
            if (!model) return;

            selectedModel = model;
            localStorage.setItem('ai_chat_model', selectedModel);
            const label = formatModelLabel(selectedModel);
            chatModelToggle.attr('title', 'Model: ' + label);
            chatModelStatus.text(label);
            chatModelOptions.find('.ai-chat-model-option').removeClass('is-active');
            chatModelOptions.find('.ai-chat-model-option').filter(function() {
                return $(this).data('model') === selectedModel;
            }).addClass('is-active');
        }

        function formatModelLabel(model) {
            return String(model || '').replace(/-\d{4}-\d{2}-\d{2}$/, '');
        }

        function renderModelOptions(models, defaultModel) {
            chatModelOptions.empty();

            if (!models || models.length === 0) {
                chatModelOptions.html('<div class="small text-muted px-1 py-2">No chat models found.</div>');
                chatModelStatus.text('Unavailable');
                return;
            }

            models.forEach(function(model) {
                const safeModel = $('<div>').text(model).html();
                const label = formatModelLabel(model);
                const safeLabel = $('<div>').text(label).html();
                const isActive = model === selectedModel || (!selectedModel && model === defaultModel);
                const item = $(`<button type="button" class="ai-chat-model-option${isActive ? ' is-active' : ''}" data-model="${safeModel}">
                    <span>${safeLabel}</span>
                    <i class="bx bx-check"></i>
                </button>`);

                chatModelOptions.append(item);
            });

            setSelectedModel(selectedModel || defaultModel || models[0]);
        }

        function truncateTitle(title) {
            const raw = String(title || '').trim();
            if (raw.length <= 11) {
                return raw;
            }

            return raw.slice(0, 11) + '...';
        }

        function loadModels(force = false) {
            if (modelsLoaded && !force) return;

            chatModelStatus.text('Loading...');

            $.ajax({
                url: "{{ route('ai.models') }}",
                method: 'GET',
                success(res) {
                    modelsLoaded = true;
                    renderModelOptions(res.models || [], res.default);

                    if (res.warning) {
                        chatModelStatus.text(res.warning);
                    }
                },
                error(xhr) {
                    modelsLoaded = false;
                    chatModelStatus.text('Failed');
                    chatModelOptions.html('<div class="small text-muted px-1 py-2">Could not load models.</div>');
                    console.error('Failed loading OpenAI models', xhr.responseText);
                }
            });
        }

        function openWidget() {
            applySavedWidgetPosition();
            chatWidget.show();
            applySavedWidgetPosition();
            chatInput.focus();
            localStorage.setItem('ai_chat_open', '1');
            loadModels();
            loadConversations();
            loadConversationHistory(currentConversationId);
        }

        chatToggle.on('click', function() {
            openWidget();
        });

        chatClose.on('click', function() {
            chatWidget.hide();
            // mark closed so reload doesn't re-open
            localStorage.setItem('ai_chat_open', '0');
        });

        chatMin.on('click', function() {
            chatWidget.toggle();
        });

        const themeState = loadTheme();
        applyTheme(themeState);
        updateThemeInputs(themeState);

        // Expand / collapse chat widget (toggle larger UI)
        const chatExpandBtn = $('#ai-chat-expand');
        function setExpanded(expanded) {
            if (expanded) {
                // increase size
                chatWidget.css({ width: '600px', 'max-width': '95%' });
                $('#ai-chat-messages').css({ height: 'min(80vh, 620px)' });
                localStorage.setItem('ai_chat_expanded', '1');
            } else {
                chatWidget.css({ width: '360px', 'max-width': '90%' });
                $('#ai-chat-messages').css({ height: '320px' });
                localStorage.setItem('ai_chat_expanded', '0');
            }
            applySavedWidgetPosition();
        }

        chatExpandBtn.on('click', function() {
            const exp = localStorage.getItem('ai_chat_expanded') === '1';
            setExpanded(!exp);
        });

        // Drag-to-move support for the chat widget (mouse + touch)
        (function() {
            const widget = $('#ai-chat-widget');
            const handle = widget.find('.ai-chat-handle');
            let dragging = false;
            let offsetX = 0;
            let offsetY = 0;

            function startDrag(ev) {
                if ($(ev.target).closest('button, a, input, textarea, select').length) return;

                ev.preventDefault();
                const e = ev.type.startsWith('touch') ? ev.originalEvent.touches[0] : ev;
                const rect = widget[0].getBoundingClientRect();

                offsetX = e.clientX - rect.left;
                offsetY = e.clientY - rect.top;

                // Switch to left/top positioning so the panel can be dropped anywhere.
                widget.css({ right: 'auto', bottom: 'auto', left: rect.left + 'px', top: rect.top + 'px', position: 'fixed' });
                dragging = true;
                widget.addClass('is-dragging');
                handle.css('cursor', 'grabbing');
            }

            function onMove(ev) {
                if (!dragging) return;
                const e = ev.type.startsWith('touch') ? (ev.originalEvent.touches[0] || ev.originalEvent.changedTouches[0]) : ev;
                let left = e.clientX - offsetX;
                let top = e.clientY - offsetY;

                const next = clampWidgetPosition(left, top);

                widget.css({ left: next.left + 'px', top: next.top + 'px' });
            }


            function endDrag() {
                if (!dragging) return;
                dragging = false;
                widget.removeClass('is-dragging');
                handle.css('cursor', 'grab');

                // persist position to localStorage
                try {
                    const rect = widget[0].getBoundingClientRect();
                    const pos = { left: Math.round(rect.left), top: Math.round(rect.top) };
                    localStorage.setItem(chatPositionKey, JSON.stringify(pos));
                } catch (e) {
                    // ignore
                }
            }

            handle.on('mousedown touchstart', startDrag);
            $(document).on('mousemove touchmove', onMove);
            $(document).on('mouseup touchend touchcancel', endDrag);
        })();

        $(window).on('resize', function() {
            applySavedWidgetPosition();
        });

        function appendMessage(sender, text) {
            const rowClass = sender === 'user' ? 'ai-chat-row ai-chat-row--user' : 'ai-chat-row ai-chat-row--assistant';
            const bubbleClass = sender === 'user' ? 'ai-chat-bubble ai-chat-bubble--user' : 'ai-chat-bubble ai-chat-bubble--assistant';

            // Escape HTML, then convert newlines to <br/> so line breaks are preserved
            const safe = $('<div>').text(text).html().replace(/\n/g, '<br/>');

            // Use wrapping styles so long words/text will break instead of forcing a scrollbar
            const el = $(`<div class="mb-2 ${rowClass}"><span class="${bubbleClass} p-2" style="display:inline-block; white-space:pre-wrap; word-break:break-word; overflow-wrap:break-word;">${safe}</span></div>`);
            chatMessages.append(el);
            chatMessages.scrollTop(chatMessages.prop('scrollHeight'));
        }

        chatSend.on('click', function() {
            const text = chatInput.val().trim();
            if (!text) return;

            const shouldRefreshTitle = !hasActiveConversationMessages;

            appendMessage('user', text);
            chatInput.val('');
            hasActiveConversationMessages = true;

            $.ajax({
                url: "{{ route('ai.chat') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                data: { message: text, conversation_id: currentConversationId, model: selectedModel },
                beforeSend() {
                    appendMessage('assistant', '...');
                },
                success(res) {
                    if (res.conversation_id) {
                        currentConversationId = res.conversation_id;
                        localStorage.setItem('ai_chat_conversation_id', String(currentConversationId));
                    }

                    if (shouldRefreshTitle && currentConversationId) {
                        loadConversations();
                        $('#ai-chat-title').text(truncateTitle(text));
                    }

                    if (res.model) {
                        setSelectedModel(res.model);
                    }

                    // remove last '...' message
                    chatMessages.find('.mb-2').last().remove();
                    appendMessage('assistant', res.reply || 'No reply');
                },
                error(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.conversation_id) {
                        currentConversationId = xhr.responseJSON.conversation_id;
                        localStorage.setItem('ai_chat_conversation_id', String(currentConversationId));
                    }

                    if (xhr.responseJSON && xhr.responseJSON.model) {
                        setSelectedModel(xhr.responseJSON.model);
                    }

                    chatMessages.find('.mb-2').last().remove();
                    appendMessage('assistant', 'Error: AI unavailable');
                }
            });
        });

        // send on enter
        chatInput.on('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                chatSend.click();
            }
        });

        // restore open state and position from localStorage
        (function restoreState() {
            try {
                localStorage.removeItem('ai_chat_pos');
                applySavedWidgetPosition();

                const open = localStorage.getItem('ai_chat_open');
                if (open === '1') {
                    // open widget and load history
                    openWidget();
                }

                // restore expanded state
                try {
                    const exp = localStorage.getItem('ai_chat_expanded') === '1';
                    if (typeof setExpanded === 'function') {
                        setExpanded(exp);
                    } else {
                        if (exp) {
                            $('#ai-chat-widget').css({ width: '600px', 'max-width': '95%' });
                            $('#ai-chat-messages').css({ height: 'min(80vh, 620px)' });
                        } else {
                            $('#ai-chat-widget').css({ width: '360px', 'max-width': '90%' });
                            $('#ai-chat-messages').css({ height: '320px' });
                        }
                    }
                } catch (e) {
                    // ignore
                }
            } catch (e) {
                // ignore restore errors
            }
        })();

        // Conversations management UI
        function renderConversations(list) {
            const container = $('#ai-chat-convos-container');
            container.empty();
            if (!list || list.length === 0) {
                container.html('<div class="small text-muted">No conversations yet.</div>');
                return;
            }

            list.forEach(function(c) {
                const title = $('<div>').text(c.title || ('Conversation ' + c.id)).html();
                const item = $(`<div class="d-flex align-items-center justify-content-between mb-2">
                    <div style="flex:1;"><a href="#" class="ai-convo-select" data-id="${c.id}">${title}</a></div>
                    <div><button class="btn btn-sm btn-outline-danger ai-convo-delete" data-id="${c.id}">Delete</button></div>
                </div>`);
                container.append(item);
            });

            // bind select
            container.find('.ai-convo-select').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                currentConversationId = id;

                chatMessages.empty();
                $.ajax({
                    url: "{{ route('ai.conversations.select', ['id' => '__ID__']) }}".replace('__ID__', id),
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    success(res) {
                        if (res.conversation && res.conversation.title) {
                            $('#ai-chat-title').text(res.conversation.title);
                        }

                        const msgs = res.messages || [];
                        if (msgs.length === 0) {
                            chatMessages.html('<div class="text-muted small">No messages yet.</div>');
                        } else {
                            msgs.forEach(m => {
                                const role = m.role || (m[0] || 'assistant');
                                const content = m.content || (Array.isArray(m) ? m[1] : (m.message || m.text || ''));
                                appendMessage(role === 'user' ? 'user' : 'assistant', content);
                            });
                        }
                        $('#ai-chat-convos-list').hide();
                        // persist selection
                        localStorage.setItem('ai_chat_conversation_id', String(currentConversationId));
                    },
                    error(xhr) {
                        Swal.fire({ title: 'Failed', text: xhr.responseJSON?.error || 'Failed to load conversation', icon: 'error' });
                    }
                });
            });

            // bind delete with SweetAlert
            container.find('.ai-convo-delete').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Delete this conversation?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ url('admin/v1/ai/conversations') }}/" + id,
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        success() {
                            Swal.fire({ title: 'Deleted', icon: 'success', timer: 900, showConfirmButton: false });
                            loadConversations();
                            if (currentConversationId == id) {
                                currentConversationId = null;
                                chatMessages.empty();
                                localStorage.removeItem('ai_chat_conversation_id');
                            }
                        },
                        error(xhr) {
                            Swal.fire({ title: 'Failed', text: xhr.responseJSON?.error || 'Delete failed', icon: 'error' });
                        }
                    });
                });
            });
        }

        function loadConversations() {
            $.ajax({
                url: "{{ route('ai.conversations') }}",
                method: 'GET',
                success(res) {
                    renderConversations(res.conversations || []);
                }
            });
        }

        $('#ai-chat-convos-toggle').on('click', function(e) {
            e.preventDefault();
            const el = $('#ai-chat-convos-list');
            if (el.is(':visible')) {
                el.hide();
            } else {
                el.show();
                loadConversations();
            }
        });

        chatModelToggle.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            chatModelList.toggle();
            loadModels();
        });

        chatThemeToggle.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            chatThemePanel.toggle();
        });

        chatThemePanel.on('input change', 'input, select', function() {
            const nextTheme = {
                bg: chatThemeBg.val(),
                text: chatThemeText.val(),
                headerBg: chatThemeHeaderBg.val(),
                headerText: chatThemeHeaderText.val(),
                font: chatThemeFont.val(),
            };
            applyTheme(nextTheme);
            saveTheme(nextTheme);
        });

        chatThemeReset.on('click', function(e) {
            e.preventDefault();
            localStorage.removeItem(chatThemeKey);
            applyTheme({});
            updateThemeInputs(defaultTheme);
        });

        chatModelList.on('click', '.ai-chat-model-option', function(e) {
            e.preventDefault();
            setSelectedModel($(this).data('model'));
            chatModelList.hide();
            chatInput.focus();
        });

        $('#ai-chat-new-convo').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('ai.conversations.create') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                data: { title: 'Conversation ' + Math.floor(Math.random()*1000) },
                success(res) {
                    if (res.conversation && res.conversation.id) {
                        currentConversationId = res.conversation.id;
                        $('#ai-chat-title').text('New conversation');
                        localStorage.setItem('ai_chat_conversation_id', String(currentConversationId));
                        loadConversations();
                        loadConversationHistory(currentConversationId);
                        hasActiveConversationMessages = false;
                        $('#ai-chat-convos-list').hide();
                    }
                }
            });
        });

        // close button inside convo list
        $('#ai-chat-convos-close').on('click', function(e) {
            e.preventDefault();
            $('#ai-chat-convos-list').hide();
        });

        // hide convo list when clicking outside
        $(document).on('click', function(e) {
            const target = $(e.target);
            const list = $('#ai-chat-convos-list');
            const toggle = $('#ai-chat-convos-toggle');

            if (list.is(':visible') && target.closest('#ai-chat-convos-list').length === 0 && target.closest('#ai-chat-convos-toggle').length === 0) {
                list.hide();
            }

            if (chatModelList.is(':visible') && target.closest('#ai-chat-model-list').length === 0 && target.closest('#ai-chat-model-toggle').length === 0) {
                chatModelList.hide();
            }

            if (chatThemePanel.is(':visible') && target.closest('#ai-chat-theme-panel').length === 0 && target.closest('#ai-chat-theme-toggle').length === 0) {
                chatThemePanel.hide();
            }
        });

        // prevent clicks inside panel from closing it
        $('#ai-chat-convos-list').on('click', function(e) {
            e.stopPropagation();
        });

        chatModelList.on('click', function(e) {
            e.stopPropagation();
        });

        chatThemePanel.on('click', function(e) {
            e.stopPropagation();
        });

        // allow Esc to close convo panel
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#ai-chat-convos-list').hide();
                chatModelList.hide();
                chatThemePanel.hide();
            }
        });
    });
</script>
