<div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('society.house.store',$society) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">House No.</label>
                        </x-slot>
                        <input required type="text" class="form-control" name="house_no"  />
                        @error('house_no')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label">ADDRESS</label>
                        </x-slot>
                        <textarea name="address" required></textarea>
                        @error('address')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label">CAPACITY</label>
                        </x-slot>
                        <input required type="text" class="form-control" name="capacity" />
                        @error('capacity')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >OWNER</label>
                        </x-slot>
                        <select name="owner" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name}}</option>
                            @endforeach
                        </select>
                        @error('owner')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >RESIDENCE</label>
                        </x-slot>
                        <select name="resident" required>
                            @foreach($users as $user)
                              
                                    <option value="{{ $user->id }}">{{ $user->name}}</option>
                               
                            @endforeach
                        </select>
                        @error('resident')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >IMAGE</label>
                        </x-slot>
                        <input type="file" name="image">
                        @error('image')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >TOTAL_MEMBER</label>
                        </x-slot>
                        <input type="text" name="total_member" required >
                        @error('owner')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">CREATE</button>
            </div>
        </form>
    </div>
</div>