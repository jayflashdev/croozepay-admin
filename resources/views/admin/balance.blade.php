@extends('admin.layouts.master')
@section('title', 'API Balances')

@section('content')
<div class="row">
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Msorg1 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="msorg1Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Msorg2 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="msorg2Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Msorg3 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="msorg3Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Msorg4 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="msorg4Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Msorg5 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="msorg5Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Msorg6 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="msorg6Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Adex1 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="adex1Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Adex2 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="adex2Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Adex3 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="adex3Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Adex4 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="adex4Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Adex5 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="adex5Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Adex6 Balance</h4>
                <h4 class="mt-3 mb-2"><b id="adex6Bal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Easyaccess </h4>
                <h4 class="mt-3 mb-2"><b id="easyaccessBal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Clubkonnect </h4>
                <h4 class="mt-3 mb-2"><b id="clubkonnectBal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    {{-- <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Ibrolinks Balance</h4>
                <h4 class="mt-3 mb-2"><b id="ibrolinksBal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div> --}}
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Ncwallet Balance</h4>
                <h4 class="mt-3 mb-2"><b id="ncwalletBal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Vtpass Balance</h4>
                <h4 class="mt-3 mb-2"><b id="vtpassBal">loading...</b></h4>
                <p class="text-muted mb-0 mt-3"></p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('breadcrumb')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">@yield('title')</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection
@section('styles')
<style>
    .man-img{ width:100%;height: auto;    }
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
@section('scripts')
    <script>
        const balanceIds = [
            { id: 'msorg1Bal', apiEndpoint: '{{route("admin.bills.balance", "msorg1")}}' },
            { id: 'msorg2Bal', apiEndpoint: '{{route("admin.bills.balance", "msorg2")}}' },
            { id: 'msorg3Bal', apiEndpoint: '{{route("admin.bills.balance", "msorg3")}}' },
            { id: 'msorg4Bal', apiEndpoint: '{{route("admin.bills.balance", "msorg4")}}' },
            { id: 'msorg5Bal', apiEndpoint: '{{route("admin.bills.balance", "msorg5")}}' },
            { id: 'msorg6Bal', apiEndpoint: '{{route("admin.bills.balance", "msorg6")}}' },
            { id: 'adex1Bal', apiEndpoint: '{{route("admin.bills.balance", "adex1")}}' },
            { id: 'adex2Bal', apiEndpoint: '{{route("admin.bills.balance", "adex2")}}' },
            { id: 'adex3Bal', apiEndpoint: '{{route("admin.bills.balance", "adex3")}}' },
            { id: 'adex4Bal', apiEndpoint: '{{route("admin.bills.balance", "adex4")}}' },
            { id: 'adex5Bal', apiEndpoint: '{{route("admin.bills.balance", "adex5")}}' },
            { id: 'adex6Bal', apiEndpoint: '{{route("admin.bills.balance", "adex6")}}' },
            { id: 'easyaccessBal', apiEndpoint: '{{route("admin.bills.balance", "easyaccess")}}' },
            { id: 'clubkonnectBal', apiEndpoint: '{{route("admin.bills.balance", "clubkonnect")}}' },
            { id: 'ncwalletBal', apiEndpoint: '{{route("admin.bills.balance", "ncwallet")}}' },
            { id: 'vtpassBal', apiEndpoint: '{{route("admin.bills.balance", "vtpass")}}' }
        ];
        const fetchBalances = async () => {
            balanceIds.forEach(async ({ id, apiEndpoint }) => {
                try {
                    const response = await fetch(apiEndpoint);
                    const data = await response.json();
                    document.getElementById(id).textContent = formatPrice(data.balance) || 0;
                } catch (error) {
                    // console.error(`Error fetching balance for ${id}:`, error);
                    document.getElementById(id).textContent = 'Error';
                }
            });
        };

        const formatPrice = (price) => {
            return price;
        };
        fetchBalances();
    </script>
@endsection
