<!-- role actions -->
<div class="dropdown">
    <button class="btn btn-sm btn-secondary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="{{ route('roles.show', $role->id) }}">
                <i class="bi bi-eye"></i>
                View</a></li>
        <li><a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">
                <i class="bi bi-pencil-square"></i>
                Edit</a></li>
        <li>
            <form class="w-100" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                    <i class="bi bi-trash"></i>
                    Delete</button>
            </form>
        </li>
    </ul>
</div>
