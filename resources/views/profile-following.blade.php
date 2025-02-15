<x-profile :sharedData="$sharedData" docTitle="Who {{$sharedData['username']}} Follows">
    @include("profile-following-only");
</x-profile>