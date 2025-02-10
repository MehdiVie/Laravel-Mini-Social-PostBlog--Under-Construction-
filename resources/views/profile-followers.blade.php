<x-profile :sharedData="$sharedData">
    <div class="list-group">
      @foreach($followers as $follower)
          <a href="/profile/{{$follower->userDofollow->username}}" class="list-group-item list-group-item-action">
              <img class="avatar-tiny" src="{{$follower->userDofollow->avatar}}" />
              <strong>{{$follower->userDofollow->username}}</strong> on {{$follower->created_at->format('n/j/Y')}}
          </a>
      @endforeach
    </div>
</x-profile>