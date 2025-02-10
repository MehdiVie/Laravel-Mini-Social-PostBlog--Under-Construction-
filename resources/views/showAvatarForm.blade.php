<x-layout>
    <div class="container container--narrow py-md-5">
        <h2 class="text-center mb-3">Change Avatar</h2>
        <form action="/change-avatar" method="POST" 
        enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input required type="file" name="avatar">
                @error('avatar')
                    <p class="alert small alert-danger shadow-sm">
                        {{$message}}
                    </p>
                @enderror
                <p >
                    (Max: 4M)
                </p>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</x-layout>