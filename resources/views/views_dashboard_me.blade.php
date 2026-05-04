@extends('Layout.Layout')
@section('title', autoTranslate('Dashboard'))

@section('content')
<style>
    /* =============================================
       LAYOUT
    ============================================= */
    .dashboard-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 1024px) {
        .dashboard-container {
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }
    }

    /* =============================================
       SKELETON
    ============================================= */
    .skeleton {
        background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 0.5rem;
    }
    .dark .skeleton {
        background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
        background-size: 200px 100%;
    }
    .skeleton-circle  { border-radius: 50%; }
    .skeleton-text    { height: 14px; margin-bottom: 8px; }
    .skeleton-title   { height: 20px; width: 60%; margin-bottom: 12px; }
    .skeleton-image   { width: 100%; height: 200px; border-radius: 12px; }
    .skeleton-card    { background: white; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; }
    .dark .skeleton-card { background: #1f2937; border-color: #374151; }
    .skeleton-pulse   { animation: skeletonPulse 2s ease-in-out infinite; }

    /* =============================================
       CREATE POST BOX  (typing animation area)
    ============================================= */
    .create-post-box {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
    }
    .dark .create-post-box {
        background: #1f2937;
        border-color: #374151;
    }

    .create-post-trigger {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.65rem 1.1rem;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 9999px;
        cursor: pointer;
        text-align: left;
        transition: background .15s, border-color .15s;
        font-size: 0.875rem;
        color: #9ca3af;
        outline: none;
        white-space: nowrap;
        overflow: hidden;
    }
    .dark .create-post-trigger {
        background: #374151;
        border-color: #4b5563;
        color: #6b7280;
    }
    .create-post-trigger:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }
    .dark .create-post-trigger:hover {
        background: #4b5563;
    }

    .typed-placeholder { color: #9ca3af; }
    .dark .typed-placeholder { color: #6b7280; }
    .typing-cursor {
        display: inline-block;
        width: 2px;
        height: 14px;
        background: #6366f1;
        margin-left: 1px;
        border-radius: 1px;
        vertical-align: middle;
        animation: blink .85s steps(1) infinite;
    }

    .action-divider {
        height: 1px;
        background: #f3f4f6;
        margin: 0.9rem 0;
    }
    .dark .action-divider { background: #374151; }

    .post-action-row {
        display: flex;
        gap: 0.6rem;
    }
    .post-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex: 1;
        padding: 0.55rem 0;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background: white;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: background .15s, border-color .15s, color .15s;
    }
    .dark .post-action-btn {
        background: #1f2937;
        border-color: #374151;
        color: #9ca3af;
    }
    .post-action-btn.btn-photo:hover  { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
    .post-action-btn.btn-photo:hover svg { color: #1d4ed8; }
    .post-action-btn.btn-article:hover { background: #fdf2f8; border-color: #f9a8d4; color: #9d174d; }
    .post-action-btn.btn-article:hover svg { color: #9d174d; }
    .btn-photo-icon   { color: #3b82f6; }
    .btn-article-icon { color: #ec4899; }

    /* =============================================
       MODAL SHARED
    ============================================= */
    .cp-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,.5);
        align-items: center;
        justify-content: center;
        animation: fadeIn .25s ease;
    }
    .cp-modal-overlay.show { display: flex; }

    .cp-modal {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 20px 40px rgba(0,0,0,.15);
        width: 92%;
        max-width: 560px;
        max-height: 70vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: slideUp .25s ease;
    }
    .dark .cp-modal { background: #1f2937; }

    .cp-modal-header {
        padding: 1.2rem 1.4rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .dark .cp-modal-header { border-color: #374151; }

    .cp-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: .02em;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .badge-photo   { background: #dbeafe; color: #1e40af; }
    .badge-article { background: #fce7f3; color: #9d174d; }
    .badge-post    { background: #ede9fe; color: #5b21b6; }

    .cp-modal-title { font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 2px; }
    .dark .cp-modal-title { color: #f9fafb; }
    .cp-modal-sub   { font-size: 0.8125rem; color: #6b7280; margin: 0; }
    .dark .cp-modal-sub { color: #9ca3af; }

    .cp-close-btn {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 2px;
        display: flex;
        transition: color .15s;
        flex-shrink: 0;
    }
    .cp-close-btn:hover { color: #374151; }
    .dark .cp-close-btn:hover { color: #e5e7eb; }

    .cp-modal-body {
        padding: 1.25rem 1.4rem;
        overflow-y: auto;
        flex: 1;
    }

    .cp-field { margin-bottom: 1rem; }
    .cp-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }
    .dark .cp-label { color: #d1d5db; }
    .cp-req { color: #ef4444; }
    .cp-opt { font-weight: 400; color: #9ca3af; }

    .cp-input, .cp-textarea {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background: white;
        color: #111827;
        font-size: 0.875rem;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        font-family: inherit;
    }
    .dark .cp-input, .dark .cp-textarea {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    .cp-input:focus, .cp-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .cp-textarea { resize: vertical; min-height: 90px; }

    /* Upload area */
    .cp-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 2rem 1.5rem;
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        cursor: pointer;
        text-align: center;
        transition: background .15s, border-color .15s;
    }
    .dark .cp-upload-label { border-color: #4b5563; }
    .cp-upload-label:hover { background: #f0f4ff; border-color: #93c5fd; }
    .dark .cp-upload-label:hover { background: #1e3a5f1a; border-color: #3b82f6; }
    .cp-upload-icon  { color: #3b82f6; }
    .cp-upload-main  { font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0; }
    .dark .cp-upload-main { color: #d1d5db; }
    .cp-upload-hint  { font-size: 0.75rem; color: #9ca3af; margin: 0; }

    .cp-preview-wrap {
        margin-top: 0.75rem;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        position: relative;
        display: none;
    }
    .dark .cp-preview-wrap { border-color: #374151; }
    .cp-preview-wrap img { width: 100%; display: block; max-height: 280px; object-fit: cover; }
    .cp-preview-remove {
        position: absolute;
        top: 8px; right: 8px;
        background: rgba(0,0,0,.6);
        color: white;
        border: none;
        border-radius: 9999px;
        width: 28px; height: 28px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: background .15s;
    }
    .cp-preview-remove:hover { background: rgba(0,0,0,.85); }

    /* Link input with prefix */
    .cp-link-wrap {
        display: flex;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: border-color .15s, box-shadow .15s;
    }
    .dark .cp-link-wrap { border-color: #4b5563; }
    .cp-link-wrap:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .cp-link-prefix {
        padding: 0.625rem 0.875rem;
        background: #f9fafb;
        font-size: 0.8125rem;
        color: #9ca3af;
        border-right: 1px solid #e5e7eb;
        white-space: nowrap;
    }
    .dark .cp-link-prefix { background: #374151; border-color: #4b5563; }
    .cp-link-field {
        flex: 1;
        padding: 0.625rem 0.875rem;
        background: white;
        border: none;
        outline: none;
        font-size: 0.875rem;
        color: #111827;
        font-family: inherit;
    }
    .dark .cp-link-field { background: #374151; color: #f9fafb; }
    .cp-link-hint { font-size: 0.75rem; color: #9ca3af; margin: 5px 0 0; }

    /* Extra item row */
    .cp-extra-section {
        border-top: 1px solid #f3f4f6;
        padding-top: 1rem;
        margin-top: 0.25rem;
    }
    .dark .cp-extra-section { border-color: #374151; }
    .cp-extra-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.875rem;
    }
    .cp-extra-title { font-size: 0.8125rem; font-weight: 500; color: #6b7280; }
    .cp-add-item-btn {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        font-family: inherit;
        transition: background .15s;
    }
    .cp-add-item-btn:hover { background: #ede9fe; border-color: #c4b5fd; color: #5b21b6; }
    .dark .cp-add-item-btn { background: #374151; border-color: #4b5563; color: #9ca3af; }
    .cp-item-row {
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        border-radius: 0.5rem;
        padding: 0.875rem;
        margin-bottom: 0.75rem;
    }
    .dark .cp-item-row { background: #111827; border-color: #374151; }
    .cp-item-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.625rem;
    }
    .cp-item-select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 5px 10px;
        background: white;
        color: #374151;
        font-size: 0.8125rem;
        outline: none;
        font-family: inherit;
    }
    .dark .cp-item-select { background: #374151; border-color: #4b5563; color: #d1d5db; }
    .cp-item-remove {
        font-size: 0.75rem;
        color: #ef4444;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    /* Modal footer */
    .cp-modal-footer {
        padding: 1rem 1.4rem;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: flex-end;
        gap: 0.625rem;
        flex-shrink: 0;
    }
    .dark .cp-modal-footer { border-color: #374151; }
    .cp-btn-cancel {
        padding: 0.5rem 1.1rem;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        background: white;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        font-family: inherit;
        transition: background .15s;
    }
    .dark .cp-btn-cancel { background: #374151; border-color: #4b5563; color: #d1d5db; }
    .cp-btn-cancel:hover { background: #f9fafb; }
    .cp-btn-submit {
        padding: 0.5rem 1.4rem;
        border-radius: 0.5rem;
        border: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
        cursor: pointer;
        font-family: inherit;
        transition: background .15s, transform .1s;
    }
    .cp-btn-submit:active { transform: scale(.98); }
    .cp-btn-submit.submit-photo   { background: #2563eb; }
    .cp-btn-submit.submit-photo:hover   { background: #1d4ed8; }
    .cp-btn-submit.submit-article { background: #be185d; }
    .cp-btn-submit.submit-article:hover { background: #9d174d; }
    .cp-btn-submit.submit-post    { background: #4f46e5; }
    .cp-btn-submit.submit-post:hover    { background: #4338ca; }

    /* =============================================
       POST FEED CARDS
    ============================================= */
    .comment-section {
        display: none;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
    }
    .dark .comment-section { border-top-color: #374151; }
    .post-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }

    /* =============================================
       COMMENT SECTION ENHANCED STYLES (from file-1)
    ============================================= */
    .comment-item {
        transition: all 0.2s ease;
        background: transparent;
    }
    .comment-item:hover {
        background: rgba(0, 0, 0, 0.02);
    }
    .dark .comment-item:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .replies-container {
        border-left: 2px solid #e5e7eb;
        margin-left: 28px;
        padding-left: 16px;
    }
    .dark .replies-container {
        border-left-color: #374151;
    }

    .comments-container {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .comments-container::-webkit-scrollbar { width: 4px; }
    .comments-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .comments-container::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 10px; }
    .dark .comments-container::-webkit-scrollbar-track { background: #374151; }
    .dark .comments-container::-webkit-scrollbar-thumb { background: #6366f1; }

    .edit-form textarea, .reply-form-container textarea {
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }
    .comment-text {
        word-break: break-word;
        white-space: pre-wrap;
        line-height: 1.5;
    }

    /* Loading spinner */
    .comment-loading {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid #e5e7eb;
        border-top: 2px solid #6366f1;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .btn-loading {
        opacity: 0.7;
        pointer-events: none;
        position: relative;
    }
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 14px; height: 14px;
        top: 50%; left: 50%;
        margin-left: -7px; margin-top: -7px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    .comment-action-btn { transition: all 0.2s ease; opacity: 0.7; }
    .comment-action-btn:hover { opacity: 1; transform: translateY(-1px); }

    .reply-form-container { transition: all 0.2s ease; }
    .reply-form-container.hidden { display: none; }

    .comment-input:focus, .reply-form-container textarea:focus, .edit-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    .delete-comment-btn:hover { color: #ef4444; }
    .edit-comment-btn:hover   { color: #3b82f6; }
    .reply-btn:hover          { color: #6366f1; }

    /* =============================================
       KEYFRAMES
    ============================================= */
    @keyframes fadeIn       { from{opacity:0} to{opacity:1} }
    @keyframes slideUp      { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
    @keyframes blink        { 0%,100%{opacity:1} 50%{opacity:0} }
    @keyframes skeletonPulse { 0%,100%{opacity:1} 50%{opacity:.6} }
    @keyframes shimmer      { 0%{background-position:-200px 0} 100%{background-position:calc(200px + 100%) 0} }
</style>

<div class="min-h-screen dark:bg-gray-800 rounded-2xl py-4 sm:py-6 px-3 sm:px-6 lg:px-8" data-dashboard-type="me">
    <div class="max-w-7xl mx-auto">
        <div class="dashboard-container">

            <!-- ============================================================
                 LEFT COLUMN
            ============================================================ -->
            <div class="feed-column">

                <!-- Statistic Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

                    <!-- Learning Corner stat -->
                    <div id="total-lrn-skeleton" class="space-y-4">
                        <div class="skeleton-card skeleton-pulse h-52">
                            <div class="flex items-center justify-between mb-3">
                                <div class="skeleton w-1/2 h-8"></div>
                                <div class="skeleton w-6 h-6 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div id="total-lrn-wrapper" class="hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">{{autotranslate("Semua Learning Corner")}}</h3>
                                <span class="text-purple-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-300">{{ $totalLearning ?? 0 }}</p>
                            <div class="mt-4 h-20"><canvas id="learningChart"></canvas></div>
                        </div>
                    </div>

                    <!-- Project stat -->
                    <div id="total-pjt-skeleton" class="space-y-4">
                        <div class="skeleton-card skeleton-pulse h-52">
                            <div class="flex items-center justify-between mb-3">
                                <div class="skeleton w-1/2 h-8"></div>
                                <div class="skeleton w-6 h-6 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="total-pjt-wrapper">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                            onclick="window.location.href='{{ auth()->check() ? route('project.index') : route('project.project_user') }}';">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Semua Project</h3>
                                <span class="text-orange-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="text-3xl font-bold text-orange-600">{{ $totalProject ?? 0 }}</p>
                            <div class="mt-4 h-20"><canvas id="projectChart"></canvas></div>
                        </div>
                    </div>

                    <!-- Sertifikat stat -->
                    <div id="total-stk-skeleton" class="space-y-4">
                        <div class="skeleton-card skeleton-pulse h-52">
                            <div class="flex items-center justify-between mb-3">
                                <div class="skeleton w-1/2 h-8"></div>
                                <div class="skeleton w-6 h-6 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div id="total-stk-wrapper" class="hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow cursor-pointer"
                            onclick="window.location.href='{{ auth()->check() ? route('sertifikat.index') : route('sertifikat-mahasiswa') }}';">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200">Total Semua Sertifikat</h3>
                                <span class="text-amber-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </span>
                            </div>
                            <p class="text-3xl font-bold text-amber-600">{{ $totalSertifikat ?? 0 }}</p>
                            <div class="mt-4 h-20"><canvas id="sertifikatChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================
                     CREATE POST BOX
                ============================================================ -->
                <div class="create-post-box">
                    <div class="flex items-center gap-3 mb-4">
                        @if(auth()->user()->photo_profile && file_exists(public_path('storage/' . auth()->user()->photo_profile)))
                            <img src="{{ asset('storage/' . auth()->user()->photo_profile) }}"
                                 class="w-11 h-11 rounded-full object-cover flex-shrink-0" alt="">
                        @else
                            <div class="w-11 h-11 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-base">
                                    {{ strtoupper(substr(auth()->user()->nama_mahasiswa, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        <button class="create-post-trigger" id="openPostTrigger" onclick="cpOpenModal('cpModalPost')">
                            <span class="typed-placeholder" id="cp-typed-text"></span>
                            <span class="typing-cursor" id="cp-cursor"></span>
                        </button>
                    </div>

                    <div class="action-divider"></div>

                    <div class="post-action-row">
                        <button class="post-action-btn btn-photo" onclick="cpOpenModal('cpModalPhoto')">
                            <svg class="btn-photo-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{autotranslate("Foto")}} 
                        </button>
                        <button class="post-action-btn btn-article" onclick="cpOpenModal('cpModalArtikel')">
                            <svg class="btn-article-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{autotranslate("Tulis Artikel")}} 
                        </button>
                    </div>
                </div>

                <!-- Konten Saya Title -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{autotranslate("Konten Saya")}}</h2>
                </div>

                <!-- Postingan Sendiri -->
                <div id="postingan-section" class="feed-section mb-10">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">{{autotranslate("Postingan Anda")}} </h3>
                    </div>

                    <div id="postingan-skeleton" class="space-y-6">
                        @for($i = 0; $i < 2; $i++)
                        <div class="skeleton-card skeleton-pulse">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="skeleton skeleton-circle w-10 h-10"></div>
                                <div>
                                    <div class="skeleton skeleton-text w-32"></div>
                                    <div class="skeleton skeleton-text w-20"></div>
                                </div>
                            </div>
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-image"></div>
                            <div class="skeleton skeleton-text w-full mt-3"></div>
                            <div class="skeleton skeleton-text w-3/4"></div>
                            <div class="flex gap-4 mt-4">
                                <div class="skeleton skeleton-text w-16"></div>
                                <div class="skeleton skeleton-text w-16"></div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="hidden" id="postingan-content-wrapper">
                        @if($postinganTerbaru->isEmpty())
                            <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <p class="text-gray-500 dark:text-gray-400">{{autotranslate("Belum ada postingan mahasiswa")}}</p>
                            </div>
                        @else
                            <div id="postingan-container" class="space-y-6">
                                @foreach($postinganTerbaru as $post)
                                    @php
                                        $commentCount = \App\Models\Komentar::getCommentCount($post->id_postingan);
                                    @endphp
                                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 post-card"
                                         data-post-id="{{ $post->id_postingan }}"
                                         data-share-url="{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}">

                                        <!-- Header -->
                                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('portfolio.show', ['user' => $post->user->username]) }}" class="flex items-center gap-3">
                                                    @if($post->user->photo_profile && file_exists(public_path('storage/' . $post->user->photo_profile)))
                                                        <img src="{{ asset('storage/' . $post->user->photo_profile) }}" class="w-10 h-10 rounded-full object-cover" alt="">
                                                    @else
                                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ strtoupper(substr($post->user->nama_mahasiswa, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $post->user->nama_mahasiswa }}</h4>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->tanggal->format('d M Y') }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-4 cursor-pointer" onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'">
                                            @php
                                                $content   = $post->content;
                                                $title     = '';
                                                $deskripsi = '';
                                                $imageUrl  = null;
                                                if (is_array($content)) {
                                                    foreach ($content as $item) {
                                                        if (isset($item['type'])) {
                                                            if ($item['type'] === 'title')       $title     = $item['content'] ?? '';
                                                            if ($item['type'] === 'description') $deskripsi = $item['content'] ?? '';
                                                            if ($item['type'] === 'image' && !empty($item['content']) && !$imageUrl)
                                                                $imageUrl = asset('storage/' . ltrim($item['content'], '/'));
                                                        }
                                                    }
                                                }
                                                $game = $post->game ?? null;
                                                $gameThumbnailMap = [
                                                    'matematika'       => 'assets/game-angka.svg',
                                                    'math'             => 'assets/game-angka.svg',
                                                    'puzzle'           => 'assets/game-puzzle.svg',
                                                    'tts'              => 'assets/game-tts.svg',
                                                    'teka-teki silang' => 'assets/game-tts.svg',
                                                ];
                                                $gameThumbnail = $game && isset($gameThumbnailMap[strtolower($game->game_name)])
                                                    ? $gameThumbnailMap[strtolower($game->game_name)] : '';
                                            @endphp

                                            @if($title)
                                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">{{ $title }}</h3>
                                            @endif
                                            @if($deskripsi)
                                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ Str::limit($deskripsi, 150) }}</p>
                                            @endif
                                            @if($imageUrl)
                                                <div class="mb-4 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm mt-3">
                                                    <img src="{{ $imageUrl }}" alt="Gambar postingan" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                                                </div>
                                            @endif

                                            @if($game)
                                                @php
                                                    $gameRoute = '';
                                                    $gameDisplayName = $game->game_name;
                                                    switch(strtolower($game->game_name)) {
                                                        case 'matematika': case 'math':
                                                            $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                                            $gameDisplayName = 'Matematika'; break;
                                                        case 'puzzle':
                                                            $gameRoute = route('game.puzzle', ['locale' => app()->getLocale()]);
                                                            $gameDisplayName = 'Puzzle'; break;
                                                        case 'tts': case 'teka-teki silang':
                                                            $gameRoute = route('game.tts', ['locale' => app()->getLocale()]);
                                                            $gameDisplayName = 'Teka-Teki Silang'; break;
                                                        default:
                                                            $gameRoute = route('game.matematika', ['locale' => app()->getLocale()]);
                                                    }
                                                @endphp
                                                <div class="mt-2 p-3 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset($gameThumbnail) }}" class="w-24 h-14 object-cover rounded" alt="Game Thumbnail">
                                                        <div>
                                                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $gameDisplayName }}</div>
                                                            <div class="text-xs text-gray-500">Mainkan game</div>
                                                            @if($game->score > 0)
                                                                <div class="text-xs text-green-600 dark:text-green-400 mt-1">🏆 Skor terbaik: {{ $game->score }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <a href="{{ $gameRoute }}?postingan={{ $post->id_postingan }}&game={{ $game->id_games }}"
                                                       class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm3 4v2h2V7H8zm6 0v2h2V7h-2zm-6 6v2h2v-2H8zm6 0v2h2v-2h-2z"/>
                                                        </svg>
                                                        Play
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Footer Actions -->
                                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                            <div class="flex items-center gap-6">
                                                @auth
                                                    <button class="like-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-red-500 transition"
                                                            data-postingan-id="{{ $post->id_postingan }}">
                                                        <svg class="w-5 h-5 {{ $post->likes->where('id_user', auth()->id())->count() > 0 ? 'fill-current text-red-500' : '' }}"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                        </svg>
                                                        <span class="like-count text-sm">{{ $post->likes->count() }}</span>
                                                    </button>
                                                @else
                                                    <button onclick="window.location.href='{{ route('login') }}'" class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                        </svg>
                                                        <span class="text-sm">{{ $post->likes->count() }}</span>
                                                    </button>
                                                @endauth

                                                <button class="comment-toggle flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition"
                                                        data-postingan-id="{{ $post->id_postingan }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    <span class="comment-count text-sm">{{ $commentCount }}</span>
                                                </button>

                                                <button class="share-btn flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                                    <svg fill="none" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21.707,11.293l-8-8A.99991.99991,0,0,0,12,4V7.54492A11.01525,11.01525,0,0,0,2,18.5V20a1,1,0,0,0,1.78418.62061,11.45625,11.45625,0,0,1,7.88672-4.04932c.0498-.00635.1748-.01611.3291-.02588V20a.99991.99991,0,0,0,1.707.707l8-8A.99962.99962,0,0,0,21.707,11.293ZM14,17.58594V15.5a.99974.99974,0,0,0-1-1c-.25488,0-1.2959.04932-1.56152.085A14.00507,14.00507,0,0,0,4.05176,17.5332,9.01266,9.01266,0,0,1,13,9.5a.99974.99974,0,0,0,1-1V6.41406L19.58594,12Z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <span onclick="window.location.href='{{ route('postingan.show', ['locale' => app()->getLocale(), 'id' => $post->id_postingan]) }}'"
                                                  class="text-xs text-gray-500 dark:text-gray-400 cursor-pointer hover:text-indigo-600"><span>{{ autoTranslate('Lihat detail') }}</span> →</span>
                                        </div>

                                        <!-- Comment Section (AJAX-powered, same as file-1) -->
                                        <div class="comment-section px-4 pb-4 bg-white dark:bg-gray-800 hidden rounded-b-xl" id="comments-{{ $post->id_postingan }}">
                                            @auth
                                                <div class="mb-4">
                                                    <div class="flex gap-3">
                                                        <div class="flex-1">
                                                            <textarea id="comment-input-{{ $post->id_postingan }}"
                                                                rows="2"
                                                                class="comment-input w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none text-sm transition"
                                                                placeholder="Tulis komentar..."></textarea>
                                                            <div class="flex justify-end mt-2">
                                                                <button onclick="window.submitComment({{ $post->id_postingan }})"
                                                                    class="submit-comment-btn px-5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                                    <span>Kirim</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium hover:underline">Masuk</a>
                                                        untuk berkomentar
                                                    </p>
                                                </div>
                                            @endauth

                                            <div id="comments-container-{{ $post->id_postingan }}" class="comments-container space-y-4 pr-2">
                                                <div class="text-center py-6 text-gray-400 text-sm">
                                                    <div class="comment-loading inline-block mr-2"></div>
                                                    <span>Memuat komentar...</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            <div id="postingan-pagination" class="mt-8">
                                {{ $postinganTerbaru->render('vendor.pagination.custom_ajax', ['groupName' => 'postingan']) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Project -->
                <div id="projects-section" class="mb-10">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-orange-600 rounded-full"></div>
                        <h3 class="text-lg font-semibold text-orange-600 dark:text-orange-300">{{autotranslate("Project")}} </h3>
                    </div>
                    <div id="project-skeleton" class="space-y-4">
                        @for($i = 0; $i < 2; $i++)
                        <div class="skeleton-card skeleton-pulse flex gap-4">
                            <div class="skeleton w-20 h-20 rounded-lg"></div>
                            <div class="flex-1">
                                <div class="skeleton skeleton-title w-3/4"></div>
                                <div class="skeleton skeleton-text w-full"></div>
                                <div class="skeleton skeleton-text w-1/2"></div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="hidden" id="project-content-wrapper">
                        @if($projects->isEmpty())
                            <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">{{autotranslate("Belum Ada Project")}}</p>
                            </div>
                        @else
                            <div data-pagination-group="project">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                                    @foreach($projects as $post)
                                        @include('components.card_postingan', ['post' => $post])
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $projects->render('vendor.pagination.custom_ajax', ['groupName' => 'project']) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sertifikat -->
                <div id="sertifikat-section" class="mb-10">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-green-600 rounded-full"></div>
                        <h3 class="text-lg font-semibold text-green-700 dark:text-green-300">{{autotranslate("Sertifikat")}}</h3>
                    </div>
                    <div id="sertifikat-skeleton" class="space-y-4">
                        @for($i = 0; $i < 2; $i++)
                        <div class="skeleton-card skeleton-pulse flex gap-4">
                            <div class="skeleton w-16 h-20 rounded-lg"></div>
                            <div class="flex-1">
                                <div class="skeleton skeleton-title w-1/2"></div>
                                <div class="skeleton skeleton-text w-3/4"></div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div id="sertifikat-content-wrapper" class="hidden">
                        @if($projectUsers->isEmpty())
                            <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">{{autotranslate("Belum Ada Sertifikat")}}</p>
                            </div>
                        @else
                            <div data-pagination-group="sertifikat">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($projectUsers as $post)
                                        @include('components.card_postingan', ['post' => $post])
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $projectUsers->render('vendor.pagination.custom_ajax', ['groupName' => 'sertifikat']) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="text-center text-gray-500 dark:text-gray-400 text-sm mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <span>{{autotranslate("Terakhir diperbarui")}}</span> {{ now()->format('d F Y H:i') }}
                </div>
            </div>

            <!-- ============================================================
                 RIGHT COLUMN: Sidebar
            ============================================================ -->
            <div class="sidebar-column">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 border border-gray-200 dark:border-gray-700 sticky top-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-5 bg-purple-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-purple-700 dark:text-purple-300">{{autotranslate("Learning Corner")}}</h3>
                    </div>
                    <div id="learning-skeleton-sidebar" class="space-y-4">
                        @for($i = 0; $i < 3; $i++)
                            <div class="skeleton-card skeleton-pulse">
                                <div class="skeleton skeleton-title w-3/4"></div>
                                <div class="skeleton skeleton-text w-1/2 mt-2"></div>
                            </div>
                        @endfor
                    </div>
                    <div id="learning-content-wrapper-sidebar" class="hidden">
                        @if($learningCorners->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">{{autotranslate("Belum Ada Learning Corner")}}</p>
                            </div>
                        @else
                            <div id="learning-corner-list" class="space-y-4">
                                @foreach($learningCorners->take(5) as $learning)
                                    <div onclick="window.location.href='{{ route('project.show', ['id' => $learning->project_id]) }}'"
                                         class="cursor-pointer dark:border-gray-700 border hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition">
                                        <h4 class="text-sm dark:text-gray-100 font-medium line-clamp-2">{{ autoTranslate($learning->content[0]['content'] ?? 'Learning Content') }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">{{ $learning->tanggal->translatedFormat('d M Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                {{ $learningCorners->render('vendor.pagination.custom_ajax', ['groupName' => 'learning_corner']) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: Buat Postingan
============================================================ --}}
<div id="cpModalPost" class="cp-modal-overlay" onclick="cpOverlayClick(event,'cpModalPost')">
    <div class="cp-modal">
        <div class="cp-modal-header">
            <div>
                <div class="cp-type-badge badge-post">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    {{autotranslate("Postingan Baru")}}
                </div>
                <h2 class="cp-modal-title">{{autotranslate("Buat Postingan Baru")}}</h2>
                <p class="cp-modal-sub">{{autotranslate("Bagikan pemikiran, cerita, atau pengalaman Anda")}}</p>
            </div>
            <button class="cp-close-btn" onclick="cpCloseModal('cpModalPost')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="cp-modal-body">
            <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Judul Postingan")}} <span class="cp-req">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           placeholder="Judul postingan Anda..."
                           class="cp-input">
                    @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Deskripsi")}} <span class="cp-opt">(opsional)</span></label>
                    <textarea name="deskripsi" class="cp-textarea" rows="4"
                              placeholder="Apa yang ingin Anda bagikan?">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="cp-extra-section">
                    <div class="cp-extra-header">
                        <span class="cp-extra-title">{{autotranslate("Konten Tambahan")}} <span class="cp-opt">(opsional)</span></span>
                        <button type="button" id="cp-add-item-post" class="cp-add-item-btn">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{autotranslate("Tambah Item")}}
                        </button>
                    </div>
                    <div id="cp-items-post"></div>
                </div>
                <div class="cp-modal-footer" style="padding-left:0;padding-right:0;border-top:none;margin-top:1rem;">
                    <button type="button" class="cp-btn-cancel" onclick="cpCloseModal('cpModalPost')">{{autotranslate("Batal")}}</button>
                    <button type="submit" class="cp-btn-submit submit-post">{{autotranslate("Buat Postingan")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: Posting Foto
============================================================ --}}
<div id="cpModalPhoto" class="cp-modal-overlay" onclick="cpOverlayClick(event,'cpModalPhoto')">
    <div class="cp-modal">
        <div class="cp-modal-header">
            <div>
                <div class="cp-type-badge badge-photo">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{autotranslate("Postingan Foto")}}
                </div>
                <h2 class="cp-modal-title">{{autotranslate("Bagikan Foto")}}</h2>
                <p class="cp-modal-sub">{{autotranslate("Unggah Gambar Untuk Postingan Anda")}}</p>
            </div>
            <button class="cp-close-btn" onclick="cpCloseModal('cpModalPhoto')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="cp-modal-body">
            <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="post_type" value="photo">
                <input type="hidden" name="items[0][type]" value="image">
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Judul")}} <span class="cp-req">*</span></label>
                    <input type="text" name="judul" placeholder="Judul postingan foto..." class="cp-input">
                </div>
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Deskripsi")}} <span class="cp-opt">(opsional)</span></label>
                    <textarea name="deskripsi" class="cp-textarea" rows="3" placeholder="Ceritakan tentang foto ini..."></textarea>
                </div>
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Upload Foto")}} <span class="cp-req">*</span></label>
                    <label class="cp-upload-label" for="cp-file-photo">
                        <svg class="cp-upload-icon w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="cp-upload-main">{{autotranslate("Klik untuk pilih foto")}}</p>
                        <p class="cp-upload-hint">JPG, PNG, GIF, WEBP — maks 5MB</p>
                    </label>
                    <input type="file" id="cp-file-photo" name="items[0][file]" accept="image/*"
                           onchange="cpPreviewImage(event,'cp-preview-photo-img','cp-preview-photo-wrap')">
                    <div class="cp-preview-wrap" id="cp-preview-photo-wrap">
                        <img id="cp-preview-photo-img" src="" alt="Preview foto">
                        <button type="button" class="cp-preview-remove"
                                onclick="cpRemovePreview('cp-preview-photo-img','cp-preview-photo-wrap','cp-file-photo')">✕</button>
                    </div>
                </div>
                <div class="cp-modal-footer" style="padding-left:0;padding-right:0;border-top:none;margin-top:1rem;">
                    <button type="button" class="cp-btn-cancel" onclick="cpCloseModal('cpModalPhoto')">{{autotranslate("Batal")}}</button>
                    <button type="submit" class="cp-btn-submit submit-photo">{{autotranslate("Posting Foto")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: Tulis Artikel
============================================================ --}}
<div id="cpModalArtikel" class="cp-modal-overlay" onclick="cpOverlayClick(event,'cpModalArtikel')">
    <div class="cp-modal">
        <div class="cp-modal-header">
            <div>
                <div class="cp-type-badge badge-article">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{autotranslate("Artikel")}}
                </div>
                <h2 class="cp-modal-title">{{autotranslate("Tulis Artikel")}}</h2>
                <p class="cp-modal-sub">{{autotranslate("Buat konten panjang yang informatif")}}</p>
            </div>
            <button class="cp-close-btn" onclick="cpCloseModal('cpModalArtikel')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="cp-modal-body">
            <form method="POST" action="{{ route('postingan.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="post_type" value="article">
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Judul")}} <span class="cp-req">*</span></label>
                    <input type="text" name="judul" placeholder="Judul artikel Anda..." class="cp-input">
                </div>
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Deskripsi")}} <span class="cp-opt">(opsional)</span></label>
                    <textarea name="deskripsi" class="cp-textarea" rows="4"
                              placeholder="Tuliskan ringkasan atau isi artikel..."></textarea>
                </div>
                <div class="cp-field">
                    <label class="cp-label">{{autotranslate("Link / Referensi")}} <span class="cp-opt">(opsional)</span></label>
                    <div class="cp-link-wrap">
                        <span class="cp-link-prefix">https://</span>
                        <input type="text" name="items[0][content]" class="cp-link-field" placeholder="contoh.com/artikel-saya">
                    </div>
                    <input type="hidden" name="items[0][type]" value="link">
                    <p class="cp-link-hint">{{autotranslate("Sertakan sumber referensi artikel jika ada")}} </p>
                </div>
                <div class="cp-modal-footer" style="padding-left:0;padding-right:0;border-top:none;margin-top:1rem;">
                    <button type="button" class="cp-btn-cancel" onclick="cpCloseModal('cpModalArtikel')">{{autotranslate("Batal")}} </button>
                    <button type="submit" class="cp-btn-submit submit-article">{{autotranslate("Buat Artikel")}} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// =============================================
// Global variables (from file-1)
// =============================================
window.currentUserId  = {{ auth()->id() ?? 'null' }};
window.currentUserPhoto = "{{ auth()->user()?->photo_profile ?? '' }}";
window.currentUserName  = "{{ auth()->user()?->nama_mahasiswa ?? '' }}";
window.locale = document.querySelector('html').getAttribute('lang') || 'id';
window.commentLastUpdated = {};

// =============================================
// Helper utilities
// =============================================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function getAvatarHtml(user, size = 'w-8 h-8', textSize = 'text-sm') {
    if (user && user.photo_profile && user.photo_profile !== 'null' && user.photo_profile !== '') {
        const photoPath = user.photo_profile.startsWith('http') ? user.photo_profile : `/storage/${user.photo_profile}`;
        return `<img src="${photoPath}" class="${size} rounded-full object-cover"
                     onerror="this.src='https://ui-avatars.com/api/?background=6366f1&color=fff&size=100&name=${encodeURIComponent(user.nama_mahasiswa || 'U')}'">`;
    }
    const name    = user?.nama_mahasiswa || window.currentUserName || 'User';
    const initial = name.charAt(0).toUpperCase();
    return `<div class="${size} rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 dark:text-indigo-400 ${textSize} font-semibold">${initial}</span>
            </div>`;
}

function getHeaders() {
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    };
}

// =============================================
// Load comments (AJAX)
// =============================================
window.loadComments = async function(postinganId) {
    const container = document.getElementById(`comments-container-${postinganId}`);
    if (!container) return;

    container.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm"><div class="comment-loading inline-block mr-2"></div> Memuat komentar...</div>';

    try {
        const response = await fetch(`/${window.locale}/komentar?id_postingan=${postinganId}`);
        const data     = await response.json();

        if (data && data.success === true) {
            window.commentLastUpdated[postinganId] = data.last_updated ?? null;

            let commentsArray = [];
            if (data.comments && Array.isArray(data.comments)) {
                commentsArray = data.comments;
            } else if (data.comments && data.comments.comments && Array.isArray(data.comments.comments)) {
                commentsArray = data.comments.comments;
            }

            if (commentsArray.length === 0) {
                const noCommentsText = (window.locale === 'id')
                    ? '✨ Belum ada komentar. Jadilah yang pertama!'
                    : '✨ No comments yet. Be the first!';
                container.innerHTML = `<p class="text-sm text-gray-500 dark:text-gray-400 text-center py-5">${noCommentsText}</p>`;
                return;
            }

            let html = '<div class="space-y-4">';
            commentsArray.forEach(comment => {
                html += renderCommentWithReplies(comment, 0, postinganId);
            });
            html += '</div>';
            container.innerHTML = html;

            attachCommentEventListeners(container, postinganId);
        } else {
            container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar</p>';
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        container.innerHTML = '<p class="text-center text-red-500 text-sm py-4">⚠️ Gagal memuat komentar: ' + error.message + '</p>';
    }
};

// =============================================
// Render comment + nested replies
// =============================================
function renderCommentWithReplies(comment, level, postinganId) {
    const marginLeft  = Math.min(level * 28, 56);
    const isOwnComment = window.currentUserId && comment.id_user == window.currentUserId;
    const isLoggedIn   = window.currentUserId !== null && window.currentUserId !== 'null';
    const userName     = escapeHtml(comment.user?.nama_mahasiswa || 'User');
    const commentText  = escapeHtml(comment.komentar);
    const commentId    = String(comment.id_komentar);
    postinganId        = String(postinganId);

    const replyText  = (window.locale === 'id') ? 'Balas'  : 'Reply';
    const editText   = (window.locale === 'id') ? 'Edit'   : 'Edit';
    const deleteText = (window.locale === 'id') ? 'Hapus'  : 'Delete';

    let html = `
        <div class="comment-item transition-all duration-200 py-2" data-comment-id="${commentId}" data-postingan-id="${postinganId}" style="margin-left:${marginLeft}px;">
            <div class="flex gap-3">
                ${getAvatarHtml(comment.user, 'w-8 h-8', 'text-xs')}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">${userName}</span>
                        <span class="text-xs text-gray-500">${formatDate(comment.tanggal || comment.created_at)}</span>
                    </div>
                    <p class="comment-text text-sm text-gray-700 dark:text-gray-300 mt-1.5 leading-relaxed" id="comment-text-${commentId}">${commentText}</p>
                    <div class="flex flex-wrap gap-3 mt-2">
    `;

    if (isLoggedIn) {
        html += `
                        <button class="reply-btn text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors inline-flex items-center gap-1"
                            data-action="reply" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            ${replyText}
                        </button>
        `;
    }

    if (isOwnComment) {
        html += `
                        <button class="edit-comment-btn text-xs text-blue-500 hover:text-blue-700 transition-colors inline-flex items-center gap-1"
                            data-action="edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            ${editText}
                        </button>
                        <button class="delete-comment-btn text-xs text-red-500 hover:text-red-700 transition-colors inline-flex items-center gap-1"
                            data-action="delete" data-comment-id="${commentId}" data-postingan-id="${postinganId}" data-type="full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            ${deleteText}
                        </button>
        `;
    }

    html += `
                    </div>
                </div>
            </div>
            <div id="reply-form-${commentId}" class="reply-form-container hidden mt-3 ml-11"></div>
        </div>
    `;

    if (comment.balasan && comment.balasan.length > 0) {
        html += `<div class="replies-container ml-4 mt-1">`;
        comment.balasan.forEach(reply => {
            html += renderCommentWithReplies(reply, level + 1, postinganId);
        });
        html += `</div>`;
    }

    return html;
}

// =============================================
// Event delegation for comment actions
// =============================================
function attachCommentEventListeners(container, postinganId) {
    if (!container.hasAttribute('data-delegated')) {
        container.setAttribute('data-delegated', 'true');
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const commentId = btn.getAttribute('data-comment-id');
            const pid       = btn.getAttribute('data-postingan-id');
            const action    = btn.getAttribute('data-action');

            if (action === 'reply')       window.showReplyForm(commentId, pid);
            else if (action === 'edit')   window.showEditForm(commentId, pid);
            else if (action === 'delete') window.deleteComment(commentId, pid, btn.getAttribute('data-type') || 'full');
            else if (action === 'save-edit')   window.saveEdit(commentId, pid);
            else if (action === 'cancel-edit') window.cancelEdit(commentId);
        });
    }
}

// =============================================
// Submit new comment (top-level)
// =============================================
window.submitComment = async function(postinganId) {
    const textarea  = document.getElementById(`comment-input-${postinganId}`);
    const commentText = textarea.value.trim();

    if (!commentText) {
        window.showPageInfo ? window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000) : alert('Komentar tidak boleh kosong');
        return;
    }

    const submitBtn   = textarea.closest('.flex-1')?.querySelector('.submit-comment-btn');
    const originalHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = 'Mengirim...';
        submitBtn.disabled  = true;
    }

    try {
        const response = await fetch(`/${window.locale}/komentar`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({ id_postingan: postinganId, komentar: commentText })
        });
        const data = await response.json();

        if (data.success) {
            textarea.value = '';
            await window.loadComments(postinganId);
            await updateCommentCount(postinganId, 1);
            window.showPageInfo && window.showPageInfo('Komentar berhasil ditambahkan', 'success', 2000);
        } else {
            window.showErrorAlert ? window.showErrorAlert(data.message || 'Gagal menambahkan komentar') : alert(data.message || 'Gagal menambahkan komentar');
        }
    } catch (error) {
        console.error('Error:', error);
        window.showErrorAlert ? window.showErrorAlert('Terjadi kesalahan: ' + error.message) : alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (submitBtn) {
            submitBtn.classList.remove('btn-loading');
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled  = false;
        }
    }
};

// =============================================
// Submit reply
// =============================================
async function submitReply(form, postinganId) {
    if (form.hasAttribute('data-submitting')) return;
    form.setAttribute('data-submitting', 'true');

    const textarea  = form.querySelector('textarea[name="komentar"]');
    const commentText = textarea.value.trim();
    const parentId  = form.querySelector('input[name="parent_id"]').value;

    if (!commentText) {
        window.showPageInfo ? window.showPageInfo('Balasan tidak boleh kosong', 'warning', 2000) : alert('Balasan tidak boleh kosong');
        form.removeAttribute('data-submitting');
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    const cancelBtn = form.querySelector('button[type="button"]');
    submitBtn.disabled = true;
    if (cancelBtn) cancelBtn.disabled = true;

    try {
        const response = await fetch(`/${window.locale}/komentar`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({
                id_postingan: postinganId,
                komentar: commentText,
                parent_id: parentId,
                reply_to_id: parentId
            })
        });
        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            await updateCommentCount(postinganId, 1);
            const replyContainer = document.getElementById(`reply-form-${parentId}`);
            if (replyContainer) { replyContainer.classList.add('hidden'); replyContainer.innerHTML = ''; }
            window.showPageInfo && window.showPageInfo('Balasan berhasil ditambahkan', 'success', 2000);
        } else {
            window.showErrorAlert ? window.showErrorAlert(data.message || 'Gagal menambahkan balasan') : alert(data.message || 'Gagal menambahkan balasan');
        }
    } catch (error) {
        console.error('Error:', error);
        window.showErrorAlert ? window.showErrorAlert('Terjadi kesalahan: ' + error.message) : alert('Terjadi kesalahan: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        if (cancelBtn) cancelBtn.disabled = false;
        form.removeAttribute('data-submitting');
    }
}

// =============================================
// Show reply form
// =============================================
window.showReplyForm = function(parentCommentId, postinganId) {
    parentCommentId = String(parentCommentId);
    const replyFormContainer = document.getElementById(`reply-form-${parentCommentId}`);
    if (!replyFormContainer) return;

    const sendText        = (window.locale === 'id') ? 'Kirim'  : 'Send';
    const cancelText      = (window.locale === 'id') ? 'Batal'  : 'Cancel';
    const placeholderText = (window.locale === 'id') ? 'Tulis balasan...' : 'Write a reply...';

    if (replyFormContainer.innerHTML.trim() !== '' && !replyFormContainer.classList.contains('hidden')) {
        replyFormContainer.classList.add('hidden');
        replyFormContainer.innerHTML = '';
        return;
    }

    replyFormContainer.innerHTML = `
        <form class="reply-submit-form mt-3">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            <input type="hidden" name="parent_id" value="${parentCommentId}">
            <div class="flex flex-col gap-2">
                <textarea name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm outline-none resize-none" placeholder="${placeholderText}"></textarea>
                <div class="flex gap-2 justify-end">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg transition-colors">${sendText}</button>
                    <button type="button" onclick="this.closest('.reply-form-container').classList.add('hidden'); this.closest('.reply-form-container').innerHTML = '';" class="px-4 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">${cancelText}</button>
                </div>
            </div>
        </form>
    `;
    replyFormContainer.classList.remove('hidden');

    const form = replyFormContainer.querySelector('form');
    form.onsubmit = null;
    form.onsubmit = async (e) => { e.preventDefault(); await submitReply(form, postinganId); };
};

// =============================================
// Show edit form
// =============================================
window.showEditForm = function(commentId, postinganId) {
    commentId   = String(commentId);
    postinganId = String(postinganId);

    const commentTextEl   = document.getElementById(`comment-text-${commentId}`);
    if (!commentTextEl) return;
    const originalText  = commentTextEl.innerText;
    const commentItem   = commentTextEl.closest('.comment-item');
    const existingEditForm = document.getElementById(`edit-form-${commentId}`);

    if (existingEditForm) {
        existingEditForm.remove();
        commentTextEl.style.display = 'block';
        const editBtn = commentItem.querySelector('.edit-comment-btn');
        if (editBtn) editBtn.style.display = 'inline-flex';
        return;
    }

    const saveText   = (window.locale === 'id') ? 'Simpan' : 'Save';
    const cancelText = (window.locale === 'id') ? 'Batal'  : 'Cancel';

    const editForm = document.createElement('div');
    editForm.className = 'edit-form mt-2';
    editForm.id = `edit-form-${commentId}`;
    editForm.innerHTML = `
        <textarea class="edit-textarea w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none" rows="2">${escapeHtml(originalText)}</textarea>
        <div class="flex gap-2 mt-2">
            <button class="save-edit-btn px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors"
                data-action="save-edit" data-comment-id="${commentId}" data-postingan-id="${postinganId}">${saveText}</button>
            <button class="px-3 py-1.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-400 transition-colors"
                data-action="cancel-edit" data-comment-id="${commentId}">${cancelText}</button>
        </div>
    `;

    commentTextEl.style.display = 'none';
    commentTextEl.parentNode.insertBefore(editForm, commentTextEl.nextSibling);
    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'none';
};

// =============================================
// Save edit
// =============================================
window.saveEdit = async function(commentId, postinganId) {
    commentId = String(commentId);
    const editForm = document.getElementById(`edit-form-${commentId}`);
    if (!editForm) return;

    const editTextarea = editForm.querySelector('.edit-textarea');
    if (!editTextarea) return;
    const newText = editTextarea.value.trim();

    if (!newText) {
        window.showPageInfo ? window.showPageInfo('Komentar tidak boleh kosong', 'warning', 2000) : alert('Komentar tidak boleh kosong');
        return;
    }

    const saveBtn = editForm.querySelector('.save-edit-btn');
    if (!saveBtn) return;
    const originalBtnHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<div class="comment-loading" style="width:14px;height:14px;"></div>';
    saveBtn.disabled  = true;

    try {
        const lastUpdated = window.commentLastUpdated?.[postinganId] ?? '';
        const response = await fetch(
            `/${window.locale}/komentar/update?id=${commentId}&id_postingan=${postinganId}&last_updated=${encodeURIComponent(lastUpdated)}`,
            { method: 'PUT', headers: getHeaders(), body: JSON.stringify({ komentar: newText }) }
        );
        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            window.showPageInfo && window.showPageInfo('Komentar berhasil diperbarui', 'success', 2000);
        } else if (data.code === 'STALE_DATA') {
            await window.loadComments(postinganId);
            window.showPageInfo && window.showPageInfo('Komentar diperbarui pengguna lain. Edit ulang jika perlu.', 'warning', 3000);
        } else {
            window.showErrorAlert ? window.showErrorAlert(data.message || 'Gagal mengupdate komentar') : alert(data.message || 'Gagal mengupdate komentar');
            saveBtn.innerHTML = originalBtnHtml;
            saveBtn.disabled  = false;
        }
    } catch (error) {
        console.error('Error:', error);
        window.showErrorAlert ? window.showErrorAlert('Terjadi kesalahan: ' + error.message) : alert('Terjadi kesalahan: ' + error.message);
        saveBtn.innerHTML = originalBtnHtml;
        saveBtn.disabled  = false;
    }
};

// =============================================
// Cancel edit
// =============================================
window.cancelEdit = function(commentId) {
    commentId = String(commentId);
    const commentTextEl = document.getElementById(`comment-text-${commentId}`);
    const editForm      = document.getElementById(`edit-form-${commentId}`);
    const commentItem   = commentTextEl.closest('.comment-item');
    commentTextEl.style.display = 'block';
    if (editForm) editForm.remove();
    const editBtn = commentItem.querySelector('.edit-comment-btn');
    if (editBtn) editBtn.style.display = 'inline-flex';
};

// =============================================
// Delete comment
// =============================================
window.deleteComment = async function(commentId, postinganId, type = 'full') {
    commentId   = String(commentId);
    postinganId = String(postinganId);

    const confirmMessageSingle = (window.locale === 'id') ? 'Apakah Anda yakin ingin menghapus balasan ini saja?' : 'Are you sure you want to delete this reply only?';
    const confirmMessageFull   = (window.locale === 'id') ? 'Apakah Anda yakin ingin menghapus komentar ini beserta semua balasannya?' : 'Are you sure you want to delete this comment and all its replies?';
    const confirmMessage = type === 'single' ? confirmMessageSingle : confirmMessageFull;

    if (window.showConfirm) {
        const confirmed = await window.showConfirm(confirmMessage);
        if (!confirmed) return;
    } else {
        if (!confirm(confirmMessage)) return;
    }

    if (window.showLoading) window.showLoading('Menghapus...');

    try {
        const response = await fetch(
            `/${window.locale}/komentar/destroy?id=${commentId}&id_postingan=${postinganId}&type=${type}`,
            { method: 'DELETE', headers: getHeaders() }
        );
        const data = await response.json();

        if (data.success) {
            await window.loadComments(postinganId);
            await updateCommentCount(postinganId, -1);
            window.showPageInfo && window.showPageInfo('Komentar berhasil dihapus', 'success', 2000);
        } else {
            window.showErrorAlert ? window.showErrorAlert(data.message || 'Gagal menghapus komentar') : alert(data.message || 'Gagal menghapus komentar');
        }
    } catch (error) {
        console.error('Error:', error);
        window.showErrorAlert ? window.showErrorAlert('Terjadi kesalahan: ' + error.message) : alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (window.closeLoading) window.closeLoading();
    }
};

// =============================================
// Update comment count in button
// =============================================
async function updateCommentCount(postinganId, delta) {
    const span = document.querySelector(`.comment-toggle[data-postingan-id="${postinganId}"] .comment-count`);
    if (span) span.innerText = Math.max(0, (parseInt(span.innerText) || 0) + delta);
}

// =============================================
// Comment toggle (open / close section + lazy-load)
// =============================================
function setupCommentToggleListeners() {
    document.querySelectorAll('.comment-toggle').forEach(btn => {
        btn.removeEventListener('click', window.handleCommentToggle);
        btn.addEventListener('click', window.handleCommentToggle);
    });
}

window.handleCommentToggle = async function(event) {
    const btn           = event.currentTarget;
    const postCard      = btn.closest('.post-card');
    const commentSection = postCard.querySelector('.comment-section');
    const isHidden      = commentSection.style.display !== 'block';

    commentSection.style.display = isHidden ? 'block' : 'none';

    if (isHidden) {
        const postinganId = btn.dataset.postinganId;
        if (postinganId) await window.loadComments(postinganId);
    }
};

// =============================================
// Share functionality
// =============================================
function setupShareListeners() {
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.removeEventListener('click', window.handleShare);
        btn.addEventListener('click', window.handleShare);
    });
}

window.handleShare = function(event) {
    const btn      = event.currentTarget;
    const postCard = btn.closest('.post-card');
    if (!postCard) return;

    const url   = postCard.dataset.shareUrl || window.location.href;
    const title = postCard.querySelector('h3')?.innerText || 'Postingan Menarik';

    if (navigator.share) {
        navigator.share({ title, url }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
            window.showPageInfo && window.showPageInfo('Link berhasil disalin!', 'success', 1500);
        }).catch(() => {
            window.showErrorAlert ? window.showErrorAlert('Gagal menyalin URL') : alert('Gagal menyalin URL');
        });
    }
};

// =============================================
// Like button AJAX
// =============================================
function setupLikeListeners() {
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.removeEventListener('click', window.handleLike);
        btn.addEventListener('click', window.handleLike);
    });
}

window.handleLike = async function(event) {
    event.preventDefault();
    event.stopPropagation();

    const likeBtn     = event.currentTarget;
    const postinganId = likeBtn.getAttribute('data-postingan-id');

    try {
        const response = await fetch(`/${window.locale}/postingan/toggle-like?id=${postinganId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();

        if (data.success) {
            likeBtn.querySelector('.like-count').textContent = data.like_count;
            const svg = likeBtn.querySelector('svg');
            if (data.liked) {
                likeBtn.classList.add('text-red-500');
                svg.classList.add('fill-current', 'text-red-500');
            } else {
                likeBtn.classList.remove('text-red-500');
                svg.classList.remove('fill-current', 'text-red-500');
            }
        }
    } catch (error) { console.error('Error:', error); }
};

// =============================================
// Sort posts: game posts first
// =============================================
function sortPostinganByGame() {
    const container = document.getElementById('postingan-container');
    if (!container) return;
    const posts = Array.from(container.children);
    posts.sort((a, b) => {
        const aHas = a.querySelector('.bg-teal-600') !== null;
        const bHas = b.querySelector('.bg-teal-600') !== null;
        return (aHas === bHas) ? 0 : aHas ? -1 : 1;
    });
    container.innerHTML = '';
    posts.forEach(p => container.appendChild(p));
}

// =============================================
// Skeleton reveal
// =============================================
window.addEventListener('load', function () {
    setTimeout(function () {
        ['postingan-skeleton','project-skeleton','sertifikat-skeleton',
         'learning-skeleton-sidebar','total-lrn-skeleton','total-pjt-skeleton','total-stk-skeleton']
            .forEach(id => document.getElementById(id)?.classList.add('hidden'));

        ['postingan-content-wrapper','project-content-wrapper','sertifikat-content-wrapper',
         'learning-content-wrapper-sidebar','total-lrn-wrapper','total-pjt-wrapper','total-stk-wrapper']
            .forEach(id => document.getElementById(id)?.classList.remove('hidden'));

        sortPostinganByGame();
        setupCommentToggleListeners();
        setupShareListeners();
        setupLikeListeners();
    }, 800);
});

// =============================================
// Typing animation for create-post trigger
// =============================================
(function () {
   const phrases = [
    "{{ autoTranslate('Apa yang ingin Anda bagikan hari ini?') }}",
    "{{ autoTranslate('Bagikan pengalaman terbaru Anda...') }}",
    "{{ autoTranslate('Ceritakan sesuatu yang menarik...') }}",
    "{{ autoTranslate('Tulis postingan baru sekarang...') }}"
];

    let pIdx = 0, cIdx = 0, deleting = false;
    const el = document.getElementById('cp-typed-text');
    if (!el) return;

    function tick() {
        const phrase = phrases[pIdx];
        if (!deleting) {
            cIdx++;
            el.textContent = phrase.slice(0, cIdx);
            if (cIdx === phrase.length) { deleting = true; setTimeout(tick, 2200); return; }
            setTimeout(tick, 60);
        } else {
            cIdx--;
            el.textContent = phrase.slice(0, cIdx);
            if (cIdx === 0) { deleting = false; pIdx = (pIdx + 1) % phrases.length; setTimeout(tick, 500); return; }
            setTimeout(tick, 30);
        }
    }
    tick();
})();

// =============================================
// Modal helpers
// =============================================
function cpOpenModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
function cpCloseModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
function cpOverlayClick(e, id) { if (e.target === document.getElementById(id)) cpCloseModal(id); }

// =============================================
// Photo preview
// =============================================
function cpPreviewImage(e, imgId, wrapId) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById(imgId).src = ev.target.result;
        document.getElementById(wrapId).style.display = 'block';
    };
    reader.readAsDataURL(file);
}
function cpRemovePreview(imgId, wrapId, inputId) {
    document.getElementById(imgId).src = '';
    document.getElementById(wrapId).style.display = 'none';
    document.getElementById(inputId).value = '';
}

// =============================================
// Extra items for post modal
// =============================================
let cpExtraIdx = 0;
document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('cp-add-item-post');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            const container = document.getElementById('cp-items-post');
            const row = document.createElement('div');
            row.className = 'cp-item-row';
            row.innerHTML = `
                <div class="cp-item-top">
                    <select name="items[${cpExtraIdx}][type]" class="cp-item-select cp-type-sel">
                        <option value="image">Gambar</option>
                        <option value="link">Link / Referensi</option>
                    </select>
                    <button type="button" class="cp-item-remove" onclick="this.closest('.cp-item-row').remove()">Hapus</button>
                </div>
                <div class="cp-item-body">
                    <input type="file" name="items[${cpExtraIdx}][file]" accept="image/*"
                           class="cp-file-field block w-full text-sm text-gray-500
                                  file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                  file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100">
                    <input type="url" name="items[${cpExtraIdx}][content]"
                           class="cp-input cp-link-field-extra hidden"
                           placeholder="https://example.com">
                </div>
            `;
            container.appendChild(row);
            const sel  = row.querySelector('.cp-type-sel');
            const file = row.querySelector('.cp-file-field');
            const link = row.querySelector('.cp-link-field-extra');
            sel.addEventListener('change', function () {
                file.classList.toggle('hidden', this.value !== 'image');
                link.classList.toggle('hidden', this.value !== 'link');
                file.disabled = this.value !== 'image';
                link.disabled = this.value !== 'link';
            });
            link.classList.add('hidden'); link.disabled = true;
            cpExtraIdx++;
        });
    }
});

// =============================================
// Sparkline charts
// =============================================
let cpCharts = {};
function createSparkline(canvasId, borderColor) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    if (cpCharts[canvasId]) cpCharts[canvasId].destroy();
    const data = Array.from({length: 7}, () => Math.floor(Math.random() * 40) + 10);
    cpCharts[canvasId] = new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: Array(7).fill(''),
            datasets: [{
                data, borderColor,
                backgroundColor: borderColor + '22',
                tension: 0.4, pointRadius: 0, borderWidth: 2, fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false } }
        }
    });
}
document.addEventListener('DOMContentLoaded', () => {
    createSparkline('learningChart',  '#8b5cf6');
    createSparkline('projectChart',   '#f97316');
    createSparkline('sertifikatChart','#f59e0b');
});
</script>
@endsection