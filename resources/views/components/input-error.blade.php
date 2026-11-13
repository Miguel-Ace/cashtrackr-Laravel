@props(['field'])

@error($field)
    <p class="font-bold text-red-500">{{$message}}</p>
@enderror