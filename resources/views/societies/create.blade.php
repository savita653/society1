
<div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('society.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">Name</label>
                        </x-slot>
                        <input required type="text" class="form-control" id="name" name="name" placeholder="Name" />
                        @error('name')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label">DESCRIPTION</label>
                        </x-slot>
                        <textarea name="description" required></textarea>
                        @error('description')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" >IMAGE</label>
                </x-slot>
                <input type="file" name="image">
                @error('image')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
