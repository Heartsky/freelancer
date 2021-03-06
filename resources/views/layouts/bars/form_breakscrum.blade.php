<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            @foreach( $list as $item )
                                <li class="breadcrumb-item"><a href="{{ $item["link"] }}">{{ $item["title"] }}</a></li>
                            @endforeach

                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    @if( $link_add != '')
                        <a href="{{ $link_add }}" class="btn btn-secondary">New</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
