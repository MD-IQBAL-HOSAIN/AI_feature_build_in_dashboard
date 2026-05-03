<!-- Chat icon -->
<button id="ai-chat-toggle" type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle me-2">
    <i class='bx bx-message-dots fs-22'></i>
</button>

<!-- Chat UI (hidden by default) -->
<div id="ai-chat-widget" class="ai-chat-widget">
    <div class="card shadow-sm ai-chat-card">
        <div class="card-header ai-chat-handle d-flex justify-content-between align-items-center">
            <div style="display:flex; align-items:center; gap:8px;">
                <strong id="ai-chat-title">Assistant</strong>
                <button id="ai-chat-convos-toggle" type="button" class="btn btn-sm btn">History</button>
            </div>
            <div class="ai-chat-actions">
                <button id="ai-chat-minimize" type="button" class="btn btn-sm btn-light" aria-label="Minimize chat" title="Minimize">
                    <i class="bx bx-minus"></i>
                </button>
                <button id="ai-chat-expand" type="button" class="btn btn-sm btn-light" aria-label="Expand chat" title="Expand">
                    <i class="bx bx-expand-alt"></i>
                </button>
                <button id="ai-chat-close" type="button" class="btn btn-sm btn-danger" aria-label="Close chat" title="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>

            <div id="ai-chat-convos-list" class="ai-chat-convos-list">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small">Conversations</strong>
                    <div>
                        <button id="ai-chat-new-convo" type="button" class="btn btn-sm btn">New</button>
                        <button id="ai-chat-convos-close" type="button" class="btn btn-sm btn-light ms-1" aria-label="Close conversations" title="Close">
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>
                <div id="ai-chat-convos-container"></div>
            </div>
        </div>
        <div class="card-body ai-chat-messages" id="ai-chat-messages">
            <!-- messages will be appended here -->
            <div class="text-muted small">Say hi to the AI assistant.</div>
        </div>
        <div class="card-footer ai-chat-footer d-flex">
            <button id="ai-chat-model-toggle" type="button" class="btn btn-light ai-chat-model-toggle" aria-label="Select AI model" title="Select model">
                <i class="bx bx-chip"></i>
            </button>
            <div id="ai-chat-model-list" class="ai-chat-model-list">
                <div class="ai-chat-model-list__header">
                    <strong class="small">Model</strong>
                    <span id="ai-chat-model-status" class="small text-muted">Loading...</span>
                </div>
                <div id="ai-chat-model-options"></div>
            </div>
            <button id="ai-chat-theme-toggle" type="button" class="btn btn-light ai-chat-theme-toggle" aria-label="Chat appearance" title="Appearance">
                <i class="bx bx-palette"></i>
            </button>
            <div id="ai-chat-theme-panel" class="ai-chat-theme-panel">
                <div class="ai-chat-theme-panel__header">
                    <strong class="small">Appearance</strong>
                    <button id="ai-chat-theme-reset" type="button" class="btn btn-sm btn-light">Reset</button>
                </div>
                <div class="ai-chat-theme-field">
                    <label for="ai-chat-theme-bg" class="small">Background</label>
                    <input id="ai-chat-theme-bg" type="color" class="form-control form-control-color" value="#ffffff">
                </div>
                <div class="ai-chat-theme-field">
                    <label for="ai-chat-theme-text" class="small">Text</label>
                    <input id="ai-chat-theme-text" type="color" class="form-control form-control-color" value="#1f2937">
                </div>
                <div class="ai-chat-theme-field">
                    <label for="ai-chat-theme-header-bg" class="small">Header BG</label>
                    <input id="ai-chat-theme-header-bg" type="color" class="form-control form-control-color" value="#ffffff">
                </div>
                <div class="ai-chat-theme-field">
                    <label for="ai-chat-theme-header-text" class="small">Header Text</label>
                    <input id="ai-chat-theme-header-text" type="color" class="form-control form-control-color" value="#1f2937">
                </div>
                <div class="ai-chat-theme-field">
                    <label for="ai-chat-theme-font" class="small">Font</label>
                    <select id="ai-chat-theme-font" class="form-select form-select-sm">
                        <option value="Plus Jakarta Sans, Poppins, Nunito, sans-serif">Modern Sans</option>
                        <option value="Manrope, IBM Plex Sans, sans-serif">Soft Sans</option>
                        <option value="Merriweather, Spectral, serif">Classic Serif</option>
                        <option value="Space Grotesk, Sora, sans-serif">Tech Sans</option>
                    </select>
                </div>
            </div>
            <input id="ai-chat-input" type="text" class="form-control me-2" placeholder="Type a message...">
            <button id="ai-chat-send" type="button" class="btn btn-primary">Send</button>
        </div>
    </div>
</div>
