  function bubblesort(vals, pos)
  {
    for (var i = 0; i < vals.length; ++i) {
      for (var j = vals.length - 1; j > 0; --j) {
        if (vals[j] > vals[j-1]) {
          var tempval = vals[j];
          vals[j] = vals[j-1];
          vals[j-1] = tempval;

          var temppos = pos[j];
          pos[j] = pos[j-1];
          pos[j-1] = temppos;
        }
      }
    }
  }

  function getEntryValue(data, j)
  {
    return data.childNodes[0].childNodes[j].childNodes[0].nodeValue;
  }

  function getEntry(data, key)
  {
    if (key != 'doctype') {
      var entry = $(data).find('[nodeName=bibtex\\:' + key + ']');
      return entry[0].childNodes[0].nodeValue;
    }
    var entry = data.childNodes[0].nodeName;
    return entry.substring(7); // this constant is the size of "bibtex:"
  }

  function BibTeXMLItem(xmlentry)
  {
    this.author = getEntry(xmlentry, 'author').split(' and ');
    this.title = getEntry(xmlentry, 'title');
    this.year = getEntry(xmlentry, 'year');
    this.url = getEntry(xmlentry, 'url');
    this.tag = getEntry(xmlentry, 'doctype');
    switch (this.tag) {
      case 'inproceedings':
        this.citation = getEntry(xmlentry, 'booktitle').substring(18) + ", " + getEntry(xmlentry, 'address') + ", " + this.year;
        break;
      case 'article':
        this.citation = getEntry(xmlentry, 'journal') + " " + getEntry(xmlentry, 'volume') + "(" + getEntry(xmlentry, 'number') + "), " + this.year;
        break;
      case 'techreport':
        this.citation = getEntry(xmlentry, 'type') + " " + getEntry(xmlentry, 'number') + ", " + getEntry(xmlentry, 'institution') + ", " + this.year;
        break;
      case 'phdthesis':
        this.citation = "D.Sc. thesis, " + getEntry(xmlentry, 'school') + ", " + getEntry(xmlentry, 'address') + ", " + this.year;
        break;
      case 'mastersthesis':
        this.citation = "M.Sc. dissertation, " + getEntry(xmlentry, 'school') + ", " + getEntry(xmlentry, 'address') + ", " + this.year;
        break;
      default:
      break;
    }
    return this;
  }

  var coAuthorUrl = {'Sylvain Pion': 'http://www-sop.inria.fr/members/Sylvain.Pion/', 'Johannes Singler': 'http://algo2.iti.uni-karlsruhe.de/english/singler.php', 'David L. Millman': 'http://cs.unc.edu/~dave/mySite/', 'Fernando L. B. Ribeiro': 'http://wwwp.coc.ufrj.br/~fernando/', 'Fábio Protti': 'http://www.ic.uff.br/~fabio/', 'George O. Ainsworth Jr.': 'mailto:george@coc.ufrj.br', 'José A. F. Santiago': 'mailto:santiago@coc.ufrj.br', 'Marcos M. Silvoso': 'http://wwwp.coc.ufrj.br/~silvoso/', 'Eduardo de M. R. Fairbairn': 'http://wwwp.coc.ufrj.br/~eduardo/', 'Iuri A. Ferreira': 'http://www.unifor.br/pls/oul/w_graduacao_ncm.detalhe_professor?professor=2620032&p_tipo_pagina=grad', 'Romildo D. Toledo-Filho': 'http://wwwp.coc.ufrj.br/~toledo/'};
  var coAuthorWeight = [];

  function showCoAuthors()
  {
    var coAuthorName = [];
    var num = 0;
    for (var ikeys in coAuthorUrl) {
      if (ikeys !== 'Vicente H. F. Batista') {
        coAuthorName[num] = ikeys;
        num++;
      }
    }
    var coauthtext = "";
    num = 1;
    coAuthorName.sort();

    for (var ikeys = 0; ikeys < coAuthorName.length; ++ikeys) {
      coauthtext = coauthtext + "<tr><td class='label'>[" + num + "]<\/td><td class='coauthor'><a href='" + coAuthorUrl[coAuthorName[ikeys]] + "'>" + coAuthorName[ikeys] + "<\/a><\/td><td class='entries'>" + coAuthorWeight[coAuthorName[ikeys]] + "<\/td><\/tr>";
      num = num + 1;
      $('#list-of-coauthors').append(coauthtext);
      coauthtext = "";
    }
  }

  function renderBibTeXml(xmlfile, numpubs)
  {
    // Initialize co-authors weights
    for (var ikeys in coAuthorUrl) {
      if (ikeys !== 'Vicente H. F. Batista') {
        coAuthorWeight[ikeys] = '';
      }
    }
    var entries = $(xmlfile).find('[nodeName=bibtex\\:entry]');
    var keys = $(xmlfile).find('[nodeName=bibtex\\:year]');
    var vals = [];
    var pos = [];
    for (var i = 0; i < keys.length; ++i) {
      vals[i] = keys[i].firstChild.nodeValue;
      pos[i] = i;
    }
    bubblesort(vals, pos);
    var currententry = "";
    var newentry = "";
    var text = "";
    if (numpubs < 0) {
      numpubs = entries.length;
    }
    for (var i = 0; i < numpubs; ++i) {
      currententry =  vals[i];
      text = text + "<tr class='headings'><th class='tag' colspan='2'>" + currententry + "<\/th><\/tr>";
      $('#list-of-publications').append(text);
      text = "";
      newentry = currententry;
      while (newentry == currententry && i < entries.length && i < numpubs) {
        var k = pos[i];
        var entry = new BibTeXMLItem(entries[k]);
        text = text + "<tr class='entry'>";
        text = text + "<td class='label'><a name='entry" + (entries.length - i) + "'><\/a>[" + (entries.length - i) + "]<\/td>";
        text = text + "<td class='data'>";
        text = text + "<p class='title'>" + entry.title + "<\/p>";
        if (entry.author.length > 1) {
          text = text + "<p class='author'>with ";
          for (var j = 0; j < entry.author.length - 1; ++j) {
            if (entry.author[j] != 'Vicente H. F. Batista') {
              text = text + "<a href='" + coAuthorUrl[entry.author[j]] + "'>" + entry.author[j] + "<\/a>, ";
              coAuthorWeight[entry.author[j]] += "<a href='#entry" + (entries.length - i) + "'>[" + (entries.length - i) + "] <\/a>";
            }
          }
          if (entry.author[j] != 'Vicente H. F. Batista') {
              text = text + "and <a href='" + coAuthorUrl[entry.author[j]] + "'>" + entry.author[j] + "<\/a><br>";
              coAuthorWeight[entry.author[j]] += "<a href='#entry" + (entries.length - i) + "'>[" + (entries.length - i) + "] <\/a>";
          }
        } else {
          if (entry.author[0] == 'Vicente H. F. Batista') {
            text = text + "<p class='author'>" + entry.author[0];
          } else {
            text = text + entry.author[0] + "<br>";
          }
        }
        text = text + "<\/p>";
        if (entry.url === '#') {
          text = text + "<p class='citation'>" + entry.citation + ", to appear<\/p>";
        } else {
          text = text + "<p class='citation'><a href='" + entry.url + "'>" + entry.citation + "<\/a><\/p>";
        }
        text = text + "<\/td>";
        text = text + "<\/tr>";
        $('#list-of-publications').append(text);
        text = "";
        i = i + 1;
        if (i > (entries.length - 1)) break;
        newentry =  vals[i];
      }
      i = i - 1;
    }
  }
