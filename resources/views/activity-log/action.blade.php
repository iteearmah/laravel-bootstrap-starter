<!-- user actions buttons laravel blade dropdown chibab menu -->
<div class="dropdown">
    <button class="btn btn-sm btn-secondary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="{{ route('activity-logs.show', $activity->id) }}">
                <i class="bi bi-eye"></i>
                View</a></li>
    </ul>
</div>
