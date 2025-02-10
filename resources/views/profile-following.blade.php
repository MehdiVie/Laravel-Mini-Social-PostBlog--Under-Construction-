<x-profile :sharedData="$sharedData" docTitle="Who {{$sharedData['username']}} Follows">
    <div class="list-group">
      @foreach($followings as $following)
          <a href="/profile/{{$following->userBeFollowed->username}}" class="list-group-item list-group-item-action">
              <img class="avatar-tiny" src="{{$following->userBeFollowed->avatar}}" />
              <strong>{{$following->userBeFollowed->username}}</strong> on {{$following->created_at->format('n/j/Y')}}
          </a>
      @endforeach
    </div>
</x-profile>