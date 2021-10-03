<div class="modal fade" id="invite-friend-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xs">
        <div class="modal-content custom_modal">
            <div class="modal-header">
                <h4 class="modal-title text-center">{!! trans('main.Invite Message') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body modal_body" id="invite-section">
                <?php
                    $errmsg = "";
                    $errmsg .= "<div id='error_msg' style='display:none' class='alert alert-danger alert-dismissable'>";
                    $errmsg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    $errmsg .= "<b></b><div class='text-center'><i class='fa fa-times'></i> </div></div>";
                    echo $errmsg;

                    $successmsg = "";
                    $successmsg .= "<div id='success_msg' style='display:none' class='alert alert-success alert-dismissable'>";
                    $successmsg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                    $successmsg .= "<b></b><div class='text-center'><i class='fa fa-check'></i> </div></div>";
                    echo $successmsg;
                ?>
                <form method="post" class="form-signin" id="invite_form" novalidate>
                    @csrf

                    <p class="m-b-10 pl-2" style="font-size: 1rem; color: #000">{{ trans('main.Name of friend title') }}</p>
                    <div class="form-group">
                        <input type="text"  id="invite-friend-name" class="form-control" placeholder="{{ trans('main.Name of friend') }}">
                    </div>
                    <p class="m-b-10 pl-2" style="font-size: 1rem; color: #000">{{ trans('main.Friend email address title') }}</p>
                    <div class="form-group">
                        <input type="email"  id="invite-firend-email" class="form-control" placeholder="{{ trans('main.Invite your friend by typing email address below: ') }}">
                    </div>
                    <div class="text-center">
                        <button type="button" id="invite-submit-btn" class="btn btn-primary m-auto">{{ trans('main.Invite') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>