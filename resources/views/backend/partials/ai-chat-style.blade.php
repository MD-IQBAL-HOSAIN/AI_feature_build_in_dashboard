<style>
    /* Floating AI chat defaults to the bottom-right corner and becomes free-positioned after dragging. */
    #ai-chat-widget {
        display: none;
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 360px;
        max-width: calc(100vw - 32px);
        z-index: 1050;
        --ai-chat-bg: #ffffff;
        --ai-chat-text: #1f2937;
        --ai-chat-muted: #6b7280;
        --ai-chat-accent: #3f5ad1;
        --ai-chat-header-bg: #ffffff;
        --ai-chat-header-text: #1f2937;
        --ai-chat-font: "Plus Jakarta Sans", "Poppins", "Nunito", sans-serif;
        color: var(--ai-chat-text);
        font-family: var(--ai-chat-font);
    }

    #ai-chat-widget .ai-chat-card {
        border: 0;
        border-radius: 8px;
        overflow: hidden;
        background: var(--ai-chat-bg);
        color: var(--ai-chat-text);
    }

    #ai-chat-widget .ai-chat-handle {
        cursor: grab;
        user-select: none;
        position: relative;
        background: var(--ai-chat-header-bg);
        color: var(--ai-chat-header-text);
    }

    #ai-chat-widget .ai-chat-handle #ai-chat-title {
        color: var(--ai-chat-header-text);
    }

    #ai-chat-widget .ai-chat-handle .btn {
        color: var(--ai-chat-header-text);
        border-color: rgba(15, 23, 42, 0.16);
    }

    #ai-chat-widget.is-dragging .ai-chat-handle {
        cursor: grabbing;
    }

    #ai-chat-widget .ai-chat-actions {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    #ai-chat-widget .ai-chat-actions .btn,
    #ai-chat-convos-close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        line-height: 1;
        padding: 0;
    }

    #ai-chat-widget .ai-chat-messages {
        height: 320px;
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        background: var(--ai-chat-bg);
        color: var(--ai-chat-text);
    }

    #ai-chat-widget .ai-chat-messages::-webkit-scrollbar {
        width: 0;
        height: 0;
        display: none;
    }

    #ai-chat-widget .ai-chat-footer {
        position: relative;
        gap: 8px;
        background: var(--ai-chat-bg);
    }

    #ai-chat-widget #ai-chat-input {
        background: var(--ai-chat-bg);
        color: var(--ai-chat-text);
        border-color: rgba(15, 23, 42, 0.16);
    }

    #ai-chat-widget #ai-chat-input::placeholder {
        color: var(--ai-chat-muted);
    }

    #ai-chat-widget .ai-chat-bubble {
        background: rgba(15, 23, 42, 0.08);
        color: var(--ai-chat-text);
        border-radius: 10px;
        text-align: left;
        max-width: 50%;
    }

    #ai-chat-widget .ai-chat-bubble--user {
        background: var(--ai-chat-accent);
        color: #ffffff;
    }

    #ai-chat-widget .ai-chat-row {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
    }

    #ai-chat-widget .ai-chat-row--user {
        justify-content: flex-end;
    }

    #ai-chat-widget .ai-chat-model-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        padding: 0;
        color: var(--ai-chat-accent);
        border-color: #d9dee8;
    }

    #ai-chat-widget .ai-chat-theme-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        padding: 0;
        color: var(--ai-chat-accent);
        border-color: #d9dee8;
    }

    #ai-chat-widget .ai-chat-model-list {
        display: none;
        position: absolute;
        left: 16px;
        bottom: 58px;
        width: min(300px, calc(100vw - 48px));
        max-height: 280px;
        overflow: auto;
        background: var(--ai-chat-bg);
        border: 1px solid #ddd;
        z-index: 1100;
        padding: 8px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.14);
        color: var(--ai-chat-text);
    }

    #ai-chat-widget .ai-chat-theme-panel {
        display: none;
        position: absolute;
        left: 64px;
        bottom: 58px;
        width: min(260px, calc(100vw - 48px));
        background: var(--ai-chat-bg);
        border: 1px solid #ddd;
        z-index: 1100;
        padding: 10px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.14);
        color: var(--ai-chat-text);
        border-radius: 8px;
    }

    #ai-chat-widget .ai-chat-theme-panel__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
    }

    #ai-chat-widget .ai-chat-theme-field {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        align-items: center;
        margin-bottom: 8px;
    }

    #ai-chat-widget .ai-chat-theme-field:last-child {
        margin-bottom: 0;
    }

    #ai-chat-widget .ai-chat-theme-field label {
        margin: 0;
        color: var(--ai-chat-muted);
    }

    #ai-chat-widget .ai-chat-theme-panel .form-select,
    #ai-chat-widget .ai-chat-theme-panel .form-control {
        font-family: var(--ai-chat-font);
    }

    #ai-chat-widget .ai-chat-model-list__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 4px 4px 8px;
    }

    #ai-chat-widget .ai-chat-model-option {
        width: 100%;
        border: 0;
        background: transparent;
        color: var(--ai-chat-text);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 8px 10px;
        text-align: left;
        border-radius: 6px;
        font-size: 0.875rem;
    }

    #ai-chat-widget .ai-chat-model-option .bx-check {
        opacity: 0;
        transition: opacity 0.15s ease-in-out;
    }

    #ai-chat-widget .ai-chat-model-option.is-active .bx-check {
        opacity: 1;
    }

    #ai-chat-widget .ai-chat-model-option:hover,
    #ai-chat-widget .ai-chat-model-option.is-active {
        background: rgba(64, 81, 137, 0.1);
        color: var(--ai-chat-accent);
    }

    #ai-chat-widget .ai-chat-convos-list {
        display: none;
        position: absolute;
        left: 8px;
        top: 42px;
        width: min(320px, calc(100vw - 48px));
        max-height: 300px;
        overflow: auto;
        background: var(--ai-chat-bg);
        border: 1px solid #ddd;
        z-index: 1100;
        padding: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        color: var(--ai-chat-text);
    }

    html[data-layout-mode="dark"] #ai-chat-widget {
        --ai-chat-bg: #1f242d;
        --ai-chat-text: #e5e7eb;
        --ai-chat-muted: #9ca3af;
        --ai-chat-accent: #7aa2ff;
    }

    @media (max-width: 575.98px) {
        #ai-chat-widget {
            right: 12px;
            bottom: 12px;
            width: calc(100vw - 24px);
            max-width: calc(100vw - 24px);
        }
    }
</style>
