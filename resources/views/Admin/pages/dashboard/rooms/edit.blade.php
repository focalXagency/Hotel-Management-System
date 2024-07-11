@extends('Admin.layouts.master')
@section('edit.rooms')
<style>
    .remove-image{
        cursor: pointer;
    }
    .existing-images {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 4px;
    background-color: #f9f9f9;
    }
    .image-item {
    position: relative;
    display: inline-block;
    }

    .image-item img {
        width: 100px;
        height: 100px;
        border-radius: 4px;
        display: block;
        object-fit: cover;
    }
    .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: #ff4d4d;
        color: white;
        border: none;
        padding: 2px 6px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
    }
</style>
<!-- Page Heading -->
<div class="row mb-2 ">
    <div class="col-md-6">
        <h1 class="h3 text-gray-800">Edit Room Number {{$room->code}} </h1>
    </div>
    <div class="col-md-6">
        <!-- Save Button -->
        <button type="submit" form="editform" class="btn btn-primary offset-5">
            Save Changes
        </button> 
        <a role="button" href="{{route('rooms.index')}}" class="btn btn-outline-danger ">Discard Changes</a>
    </div>
</div>

<!-- Form -->
<div class="card shadow mb-4">
    <div class="card-body">
            <form method="POST" id="editform" action="{{ route('rooms.update', $room->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4 mb-4">
                    <!-- Room Code Field -->
                    <div class="col-md-3">
                        <label for="code" class="form-label">{{ __('Code') }}</label>
                        <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                            name="code" value="{{ $room->code }}" autocomplete="code" autofocus>
                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                
                    <!-- Room Type Field -->
                    <div class="col-md-3">
                        <label for="room_type" class="form-label">{{ __('Room Type') }}</label>
                        <select name="room_type" id="room_type"
                            class="form-control @error('room_type') is-invalid @enderror"
                            name="name">{{ old('status') }}
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Status Field -->
                    <div class="col-md-2">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ $room->status == 'unavailable' ? 'selected' : '' }}>UnAvailable</option>
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Price Field -->
                    <div class="col-md-2">
                        <label for="price" class="form-label">{{ __('Price') }}</label>
                        <input value='{{$room->price}}' id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price">
                        @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Floor Field -->
                    <div class="col-md-2">
                        <label for="floorNumber" class="form-label">FloorNumber</label>
                        <select name="floorNumber" id="floorNumber" class="form-control @error('floorNumber') is-invalid @enderror">
                            <option value=""> Select Floor</option>
                            <option value="0" {{ old('floorNumber') === 0 ? 'selected' : '' }}>Ground</option>
                            @for ($floor=0;$floor<16;$floor++)
                                <option value="{{ $floor }}"  {{ (old('floorNumber'))? "selected":"" }}>{{ $floor }}</option>
                            @endfor 
                        </select>
                        @error('floorNumber')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>     

                <div class="row g-3 mb-4">
                    <!-- Display existing images with delete option -->
                    <div class="col-md-6">
                        <label>Existing Images:</label>
                        <div class="existing-images">
                        @if ($room->images)
                            @foreach (json_decode($room->images,true) as $image)
                                <div class="image-item" id="Existing-images">
                                    <img src={{asset('images/'.$image)}} alt="room {{$room->code}}">
                                    {{-- <span class="remove-image" onclick="removeImage(this,'{{$image}}')">X</span> --}}
                                    <button type="button" class="delete-btn" class="remove-image"  onclick="removeImage(this,'{{$image}}')">X</button>
                                </div> 
                            @endforeach
                        @endif
                        <!-- Hidden input to store images to delete-->
                        <input type="hidden" name="delete_images" id="delete-images">
                        </div>
                    </div>
                    <!-- Description Field -->
                    <div class="col-md-6">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea id="description" rows="6" class="form-control @error('description') is-invalid @enderror" name="description" >{{ $room->description }}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <!--File input for new images-->  
                    <div class="col-md-6">
                        <label for="new_images" class="form-label">Add New Images:</label>
                        <input id="new_images" type="file"  class="form-control @error('images') is-invalid @enderror" name="new_images[]" multiple accept="image/*" onchange="previeImages(this)">
                        <!-- Container for new Images preview-->
                        <div id="new-images-preview"></div>
                    </div>
                </div>

                {{-- <div class="row mb-3">
                    <label for="file-input" class="col-md-4 col-form-label text-md-end">{{ __('Image') }}</label>
                    <div class="col-md-6">
                        <input id="file-input" type="file"  class="form-control @error('images') is-invalid @enderror" name="images" >
                        <div id="preview-container"></div>
                        @error('images')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div> --}}
            </form>
            <br>
            <br>
            <br>
    </div>
</div>
<script>
// $(document).ready(function(){
//     $("#file-input").on("change", function(){
//         var files = $(this)[0].files;
//         $("#preview-container").empty();
//         if(files.length > 0){
//             for(var i = 0; i < files.length; i++){
//                 var reader = new FileReader();
//                 reader.onload = function(e){
//                     $("<div class='preview'><img src='" + e.target.result + "'><button class='delete'>UnCheck</button></div>").appendTo("#preview-container");
//                 };
//                 reader.readAsDataURL(files[i]);
//             }
//         }
//     });
// $("#preview-container").on("click", ".delete", function(){
//         $(this).parent(".preview").remove();
//         $("#file-input").val(""); // Clear input value if needed
//     });
// });

let deleteImages=[];

function removeImage(element,filename) {
    element.parentElement.remove();
    deleteImages.push(filename);
    document.getElementById('delete-images').value=JSON.stringify(deleteImages); 
}
function previewImages(params) {
    const previewContainer=document.getElementById('new-images-preview');
    previewContainer.innerHTML='';
    for (const file of input.files) {
        const reader = new FileReader();
        reader.onload=function(e){
            const preview=document.createElement('div');
            preview.classList.add('image-preview');
            const img=document.createElement('img');
            img.src=e.target.result;
            preview.appendChid(img);
        };
    reader.readAsDataURL(file);   
    }
}
</script>
@endsection