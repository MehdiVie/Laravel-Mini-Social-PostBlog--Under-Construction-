<x-profile :sharedData="$sharedData" docTitle="{{$sharedData['username']}}'s Posts" >
  <div class="list-group">
    @foreach($posts as $post)
        <x-post :post="$post" :hideAuthor="true" />
    @endforeach
  </div>
</x-profile>