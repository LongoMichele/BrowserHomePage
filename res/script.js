function recursiveClose(folder) {
  folder.classList.remove('open');
  var nextSibling = folder.nextElementSibling;
  while (nextSibling) {
    if (nextSibling.querySelector('.folder')) {
      recursiveClose(nextSibling.querySelector('.folder'));
    }
    nextSibling = nextSibling.nextElementSibling;
  }
}

function cycleFolder(jsonEl, parentFolder) {
  jsonEl.forEach(el => {
    if (el.type.toLowerCase() === 'folder') {
      var toAppend = '<li class="line-container"><ul id="' + el.name.replaceAll(' ', '') + '"><div class="line-container folder"><span class="line-text">' + el.name + '</span></div></ul></li>';
      document.querySelector(parentFolder).innerHTML += toAppend;
      cycleFolder(el.children, 'ul#' + (el.name.replaceAll(' ', '')));
    } else if (el.type.toLowerCase() === 'link') {
      var toAppend = '<li class="line-container"><a href="' + el.url + '" class="line-text link">' + el.name + '</a></li>';
      document.querySelector(parentFolder).innerHTML += toAppend;
    }
  });
}

window.addEventListener('load', function () {
  cycleFolder(data.data, '.links-container');

  document.querySelectorAll('.folder').forEach(folder => {
    folder.addEventListener('click', () => {
      if (folder.classList.contains('open')) {
        recursiveClose(folder);
      } else {
        folder.classList.add('open');
      }
    });
  });
});