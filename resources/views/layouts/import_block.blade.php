<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
    <label for="file">{{__('File csv')}}</label>
    <input type="file" class="form-control" id="file" name="file">

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jschardet/2.1.1/jschardet.min.js"></script>
<div class="form-group">
    <label for="type_of_file">{{__('Type of file')}}</label>
    <select class="form-control" id="type_of_file" name="type_of_file">
        <option value="0">Windows-1251</option>
        <option value="1">UTF-8</option>
    </select>
</div>
<button type="submit" class="btn btn-primary">
    {{__('Load')}}
</button>
<script>
    $(document).ready(function() {
        $('#file').change(function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var content = e.target.result;
                var detected = jschardet.detect(content);
                if (detected.encoding === 'windows-1251') {
                    $('#type_of_file').val(0);
                } else {
                    $('#type_of_file').val(1);
                }
            };
            reader.readAsArrayBuffer(file);
        });
    });
</script>