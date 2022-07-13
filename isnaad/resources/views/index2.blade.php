@include('newDesign.layout.header')

@if(auth()->user()->type=='m' ||auth()->user()->type=='s'||auth()->user()->type=='p' ||auth()->user()->type=='b')
@include('newDesign.layout.nav2')
@endif
@if(auth()->user()->type=='a')
    @include('newDesign.Client.layout.nav2')
    @endif

@include('newDesign.layout.content')

@include('newDesign.layout.footer')


