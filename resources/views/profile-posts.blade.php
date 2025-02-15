<x-profile :sharedData="$sharedData" docTitle="{{$sharedData['username']}}'s Posts" >
  @include("profile-posts-only");
</x-profile>