@extends('layouts.public')
@section('title', ($page === 'web3' ? 'Web3' : 'About').' | '.($settings['name'] ?? 'Portfolio'))
@section('content')
<section class="page-hero"><div class="shell narrow">@if($page === 'about')<p class="eyebrow">About</p><h1>Technology, design and a habit of continuous learning.</h1><div class="long-copy">{!! nl2br(e($settings['about'] ?? 'Details coming soon.')) !!}</div>@else<p class="eyebrow">Web3</p><h1>Exploring what's next.</h1><div class="long-copy"><p>I'm interested in the possibilities of blockchain, decentralized applications, digital ownership and emerging Web3 technologies.</p><p>I approach this space through exploration, learning, building and experimentation — with curiosity rather than overstatement.</p></div><div class="web3-figure" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>@endif</div></section>
@endsection
