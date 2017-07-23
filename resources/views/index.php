<?php
dump(\Globals::$user->toArray());
?>
<?php if (empty(Globals::$user)): ?>
<script type="text/javascript">
  fetch('/register', {
    method: 'POST',
    headers: {
      'X-XSRF-TOKEN': unescape(/XSRF-TOKEN=(.*)(?:;|$)/.exec(document.cookie)[1]),
      'Content-Type': 'application/json; charset=utf-8;'
    },
    credentials: 'include',
    body: JSON.stringify({
      email: 'testtesttest@outlook.com',
      password: 'xxxxxxxxx'
    })
  })
</script>
<?php endif; ?>
