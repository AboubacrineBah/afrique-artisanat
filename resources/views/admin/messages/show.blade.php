@extends('layouts.admin')

@section('title', $message->subject)
@section('heading', 'Message')

@section('content')
    <a class="btn btn-primary btn-sm mb-4" href="{{ route('admin.messages.index') }}">‹
        Retour aux messages</a>
    <div class="panel" style="max-width: 44rem">
        <h2 class="h4">{{ $message->subject }}</h2>
        <p class="text-muted small">
            De {{ $message->name }} (<a href="mailto:{{ $message->email }}">{{ $message->email }}</a>) ·
            {{ $message->created_at->format('d/m/Y à H:i') }}
        </p>
        <p style="white-space: pre-line">{{ $message->message }}</p>
        <a class="btn btn-primary btn-sm"
            href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}">Répondre par
            e-mail</a>
    </div>

@endsection