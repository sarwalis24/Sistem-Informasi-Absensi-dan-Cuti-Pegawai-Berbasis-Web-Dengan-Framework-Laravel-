@props(['id' => '', 'size' => '', 'show' => '', 'title' => ''])
<div class="modal fade" id="{{ $id }}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog {{ $size }} modal-dialog-centered modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel18">{{ $title }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div id="{{ $show }}"></div>
            </div>
        </div>
    </div>
</div>
