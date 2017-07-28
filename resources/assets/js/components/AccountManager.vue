<template>
<div class="account-manager">
<div class="dropdown">
    <a class="btn btn-primary" role="button" data-toggle="dropdown">我</a>
    <ul class="dropdown-menu" role="menu">
    </ul>
</div>
<div class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ title }}</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">{{ footerButtonText }}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
</template>

<script>
import $ from 'jquery'
import 'bootstrap'

export default {
    props: {
        loggedIn: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            title: '登录',
            footerButtonText: '登录'
        }
    },
    mounted() {
        if (!this.loggedIn) {
            // $(this.$el).find('.modal').modal({ show: true })
        }
        $(this.$el).find('.dropdown btn').dropdown()
    }
}
</script>

<style scoped>
.account-manager {
    display: inline;
}
</style>
