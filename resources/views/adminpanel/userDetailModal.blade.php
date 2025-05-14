<div class="modal-header">
    <h5 class="modal-title">Détails de l'utilisateur : {{ $user->firstname }} {{ $user->lastname }}</h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

    <!-- Infos utilisateur -->
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Status :</strong> {{ ucfirst($user->status) }}</p>
    <p><strong>Compte créé le :</strong> {{ $user->created_at->format('d M Y') }}</p>

    <hr>

    <!-- Cours achetés -->
    <h5>Cours achetés</h5>
    @forelse ($user->courses as $course)
        <div class="mb-3 border p-2 rounded">
            <strong>{{ $course->name }}</strong> ({{ $course->category }})<br>
            @php
                $videos = $course->sections->flatMap->videos;
                $total = $videos->count();
                $completed = $videos->filter(fn($v) => $v->videoProgress->where('user_id', $user->id)->first()?->is_completed)->count();
                $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
            @endphp
            <small>Progression vidéos : {{ $progress }}%</small>
        </div>
    @empty
        <p>Aucun cours acheté.</p>
    @endforelse

    <hr>

    <!-- Examens passés -->
    <h5>Examens</h5>
    @forelse ($user->examUsers as $examUser)
        <div class="mb-3 border p-2 rounded">
            <strong>{{ $examUser->exam->title }}</strong><br>
            Score : {{ $examUser->score }} –
            <span class="badge badge-{{ $examUser->status === 'passed' ? 'success' : 'danger' }}">
                {{ ucfirst($examUser->status) }}
            </span>
        </div>
    @empty
        <p>Aucun examen passé.</p>
    @endforelse

    <hr>

    <!-- Certificats -->
    <h5>Certificats</h5>
    @php $hasCertificate = false; @endphp
    @foreach ($user->examUsers as $examUser)
        @if ($examUser->certificate)
            @php $hasCertificate = true; @endphp
            <div class="mb-3 border p-2 rounded">
                <strong>{{ $examUser->exam->title }}</strong><br>
                <a href="{{ asset('storage/certificates/' . $examUser->certificate->file_path) }}" target="_blank">
                    Voir le certificat
                </a>
            </div>
        @endif
    @endforeach
    @unless($hasCertificate)
        <p>Aucun certificat disponible.</p>
    @endunless

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
</div>
