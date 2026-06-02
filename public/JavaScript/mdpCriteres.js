function initMdpCriteres(inputId, listId) {
  var input = document.getElementById(inputId);
  var list  = document.getElementById(listId);
  if (!input || !list) return;

  var rules = {
    length:  function(v) { return v.length >= 8; },
    upper:   function(v) { return /[A-Z]/.test(v); },
    lower:   function(v) { return /[a-z]/.test(v); },
    digit:   function(v) { return /[0-9]/.test(v); },
    special: function(v) { return /[^A-Za-z0-9]/.test(v); },
  };

  input.addEventListener('input', function () {
    var val = this.value;
    var items = list.querySelectorAll('li[data-rule]');
    for (var i = 0; i < items.length; i++) {
      var li   = items[i];
      var rule = li.getAttribute('data-rule');
      var icone = li.querySelector('.mdp-icone');
      if (rules[rule] && rules[rule](val)) {
        li.classList.add('valide');
        if (icone) icone.textContent = '✓';
      } else {
        li.classList.remove('valide');
        if (icone) icone.textContent = '✗';
      }
    }
  });
}
