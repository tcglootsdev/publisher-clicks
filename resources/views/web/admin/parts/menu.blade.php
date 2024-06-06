@php
    $partId = 'pt-menu';
@endphp

<div class="aside-menu flex-column-fluid ps-3 pe-1 {{ $partId }}">
    <div
        class="menu menu-sub-indention menu-column menu-rounded menu-title-gray-600 menu-icon-gray-500 menu-active-bg menu-state-primary menu-arrow-gray-500 fw-semibold fs-6 my-5 mt-lg-2 mb-lg-0"
        id="kt_aside_menu" data-kt-menu="true">
        <div class="hover-scroll-y mx-4" id="kt_aside_menu_wrapper" data-kt-scroll="true"
             data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
             data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="20px"
             data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer">
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion pt-menu-users">
                <a class="menu-link" href="{{ url('/admin/users') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-people fs-2">
                         <span class="path1"></span>
                         <span class="path2"></span>
                         <span class="path3"></span>
                         <span class="path4"></span>
                         <span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title">Users</span>
                </a>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion pt-menu-giftcards">
                <a class="menu-link" href="{{ url('/admin/giftcards') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-gift fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                             <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Gift Cards</span>
                </a>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion pt-menu-cpawebsites">
                <a class="menu-link" href="{{ url('/admin/cpawebsites') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-fasten fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">CPA Websites</span>
                </a>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion pt-menu-withdrawals">
                <a class="menu-link" href="{{ url('/admin/withdrawals') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-arrow-right-left fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Withdrawals</span>
                </a>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-sms fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Messages</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('/admin/messages/compose') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Compose</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('/admin/messages/inbox') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Inbox</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="{{ url('/admin/messages/sent') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Sent</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
