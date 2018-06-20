@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <button id="show-modal" @click="showModal = true" class="btn btn-lg btn-primary add"><i class="fa fa-plus-circle" aria-hidden="true"></i> add</button>
        </div>
    </div>
    <div id="feeds" class="row justify-content-center">
        <div class="col-md-6">
            <post-component
                user-auth-id = "{{ Auth::user()->id }}"
                user-id = ""
            >
            </post-component>
        </div>
    </div>
    <modal v-if="showModal" @close="showModal = false">

    </modal>
</div>

<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper" @click="$emit('close')">
          <div class="modal-container" @click.stop>
            <div class="modal-body">
              <slot name="body">
                <div class="card">
                    <div class="card-body">
                        <form id="form-post" enctype="multipart/form-data" method="POST" action="{{ route('user.post.store') }}">
                            @csrf
                            <post-form-component></post-form-component>
                        </form>
                    </div>
                </div>
              </slot>
            </div>

          </div>
        </div>
      </div>
    </transition>
  </script>
@endsection
