<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
        </a>

    </li>
    @if (Auth::user()->hasRole("Admin"))

    <li class="nav-item">
        <a class="nav-link" href="#navbar-customer" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-customer">
            <i class="ni ni-diamond"></i>
            <span class="nav-link-text" >{{ __('Master Management') }}</span>
        </a>

        <div class="collapse" id="navbar-customer">
            <ul class="nav nav-sm flex-column">
                @if (Auth::user()->hasRole("Admin"))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('master.company_management') }}">
                            {{ __('company.page_title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('master.staff_management') }}">
                            Staff Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('master.customer_management') }}">
                            {{ __('customer.page_title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer_merchant') }}">
                            Merchant Management
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('master.team_management')}}">
                            {{ __('team.page_title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('master.bank_account_management')}}">
                            {{ __('bank_account.page_title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('master.user_management')}}">
                            {{ __('user.page_title') }}
                        </a>
                    </li>
                @endif


            </ul>
        </div>

    </li>
    @endif
    @if (Auth::user()->hasAnyPermission(['admin','summary_work']))
        <li class="nav-item">
            <a class="nav-link" href="#navbar-summary" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-summary">
                <i class="ni ni-tag"></i>
                <span class="nav-link-text" > {{ __('summary_work.title') }}</span>
            </a>

            <div class="collapse" id="navbar-summary">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('export.team_summary') }}">
                            {{ __('summary_work.team_summary_title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('export.customer_summary')}}">
                            {{ __('summary_work.customer_summary_title') }}
                        </a>
                    </li>
                </ul>
            </div>

        </li>
    @endif

    @if (Auth::user()->hasAnyPermission(['admin','customer_invoice']))
        <li class="nav-item">
            <a class="nav-link" href="#navbar-cusotmer-invoice" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-cusotmer-invoice">
                <i class="ni ni-tag"></i>
                {{ __('invoice_customer.title') }}
            </a>
            <div class="collapse" id="navbar-cusotmer-invoice">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('export.customer_invoice') }}">
                            {{ __('invoice_customer.export_title') }}
                        </a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="#">--}}
{{--                            {{ __('invoice_customer.import_title') }}--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{route("invoice.invoice_mooney_create")}}">
                            {{ __('invoice_customer.manual_insert') }}
                        </a>
                    </li>

                </ul>
            </div>
        </li>
    @endif

    @if (Auth::user()->hasAnyPermission(['admin','import_resource']))
        <li class="nav-item">
            <a class="nav-link" href="{{route('upload_data')}}">
                <i class="ni ni-tag"></i>
                {{ "Import Resource" }}
            </a>
        </li>
    @endif

    @if (Auth::user()->hasAnyPermission(['admin','finance_summary']))
        <li class="nav-item">
            <a class="nav-link" href="{{route('finance.report')}}">
                <i class="ni ni-tag"></i>
                {{ __('finance.title') }}
            </a>
        </li>
    @endif

    @if (Auth::user()->hasAnyPermission(['admin','expense_summary', 'expense_branch']))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('expense.report') }}">
                <i class="ni ni-tag"></i>
                {{ __('Expense Report') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#navbar-bank-transaction" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-cusotmer-invoice">
                <i class="ni ni-tag"></i>
                {{ __('Bank Transaction') }}
            </a>
            <div class="collapse" id="navbar-bank-transaction">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.bank_transaction_list') }}">
                            {{ __('List') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.bank_transaction_add')}}">
                            {{ __('Add') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route("expense.bank_transaction_export")}}">
                            {{ __('Report') }}
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#navbar-cash-transaction" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-cusotmer-invoice">
                <i class="ni ni-tag"></i>
                {{ __('Cash Transaction') }}
            </a>
            <div class="collapse" id="navbar-cash-transaction">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.cash_transaction_list') }}">
                            {{ __('List') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('expense.cash_transaction_add')}}">
                            {{ __('Add') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#navbar-card-paid" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-cusotmer-invoice">
                <i class="ni ni-tag"></i>
                {{ __('Visa Paid') }}
            </a>
            <div class="collapse" id="navbar-card-paid">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.visa_paid_list') }}">
                            {{ __('List') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.visa_paid_add') }}">
                            {{ __('Add') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#navbar-not-real-paid" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-cusotmer-invoice">
                <i class="ni ni-tag"></i>
                {{ __('Expense not real paid') }}
            </a>
            <div class="collapse" id="navbar-not-real-paid">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.expense_nrp_list') }}">
                            {{ __('List') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expense.expense_nrp_add') }}">
                            {{ __('Add') }}
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    @endif

    @if (Auth::user()->hasAnyPermission(['admin','revenue_summary']))
        <li class="nav-item">
            <a class="nav-link" href="{{route('revenue.report')}}">
                <i class="ni ni-tag"></i>
                {{ __('revenue.title') }}
            </a>
        </li>
    @endif

{{--    @if (Auth::user()->hasAnyPermission(['admin','labor_union']))--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" href="#">--}}
{{--                <i class="ni ni-tag"></i>--}}
{{--                {{ __('trade_union.title') }}--}}
{{--            </a>--}}
{{--        </li>--}}
{{--    @endif--}}

    <li class="nav-item">
        <a class="nav-link" href="#navbar-user" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-user">
            <i class="ni ni-single-02" ></i>
            <span class="nav-link-text">{{ __('profile.title') }}</span>
        </a>

        <div class="collapse" id="navbar-user">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.show') }}">
                        {{ __('profile.detail') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        {{ __('profile.change_profile') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.password') }}">
                        {{ __('profile.change_password') }}
                    </a>
                </li>



            </ul>
        </div>
    </li>
</ul>
