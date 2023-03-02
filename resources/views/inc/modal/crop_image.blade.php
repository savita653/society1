<div class="modal fade" id="modal_change_photo">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title upper-title">Change Photo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body dynamic-upper-modal">
            <form id="cp_crop" method="post" action="{{ $crop_url }}">
                @csrf
                <div class="modal-body">
                    <div class="text-center" id="cp_target">Select a file. Only .jpg, .jpeg, .png files are allowed.</div>
                    <input type="hidden" name="cp_img_path" id="cp_img_path"/>
                    <input type="hidden" name="ic_x" id="ic_x"/>
                    <input type="hidden" name="ic_y" id="ic_y"/>
                    <input type="hidden" name="ic_w" id="ic_w"/>
                    <input type="hidden" name="ic_h" id="ic_h"/>
                </div>
            </form>
            <form id="cp_upload" method="post" enctype="multipart/form-data" action="{{ $upload_url }}">
                @csrf
                <div class="modal-body form-horizontal form-group-separated">
                    <div class="form-group">
                        <label class="control-label">Photo</label>
                        <div class=" custom-file">
                            
                            <input type="file" class="custom-file-input" name="file" id="cp_photo" data-filename-placement="inside" />
                            <label class="custom-file-label" for="inputGroupFile01">Select a file</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary disabled" id="cp_accept">Save</button>
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>