@extends('layouts.app')

@section('content')
<div class="container">
    @include('partials.uploader', [
        'title' => 'Upload only photos ',
        'acceptedFiles' => '.jpg,.png',
        'uploadedFiles' => $post->attachments->toArray(),
        'params' => [
            'attachable_id' => 1,
            'attachable_type' => 'App\Post'
        ],
    ])
</div>
@endsection