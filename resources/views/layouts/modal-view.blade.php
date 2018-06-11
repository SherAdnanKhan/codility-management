<form class="form-horizontal" method="POST" action= "{{ route('leave.update', $leave->id) }}">
{{ csrf_field() }}
{{ method_field('PATCH') }}

    <div class="form-group-material">
        <label for="name" class="label-material">Leave Type</label>
        <div class='input-group-material ' >
            <input type='text' id="name" name="name"   value="{{$leave->name}}" class="input-material" />
            @if ($errors->has('name'))
                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
            @endif

        </div>
    </div>
    <div class="form-group-material">
        <label for="name" class="label-material">Leave Allowed</label>
        <input type='number' id="allowed" name="allowed"   value="{{$leave->allowed}}" class="input-material" />
        @if ($errors->has('allowed'))
            <span class="help-block">
                                        <strong>{{ $errors->first('allowed') }}</strong>
                                    </span>
        @endif

    </div>
    <div class="form-group-material">
        <label for="color" class="label-material">Color</label>
        <input type="color" name="color_code" class=" col-2" value="{{$leave->color_code}}"/>
        @if ($errors->has('color_code'))
            <span class="help-block">
                                        <strong>{{ $errors->first('color_code') }}</strong>
                                    </span>
        @endif
    </div>
    <button type="submit" class="btn btn-outline-success">Update Leaves</button>
</form>
