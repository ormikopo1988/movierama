import $ from 'jquery';

var MOVIERAMA = MOVIERAMA || {};

MOVIERAMA.namespace = function (ns_string) {
  var parts = ns_string.split('.'),
      parent = MOVIERAMA,
      i;

  // strip redundant leading global
  if (parts[0] === 'MOVIERAMA') {
    parts = parts.slice(1);
  }

  for (i = 0; i < parts.length; i += 1) {
    // create a property if it doesn't exist
    if (typeof parent[parts[i]] === 'undefined') {
      parent[parts[i]] = {};
    }
    parent = parent[parts[i]];
  }
  return parent;
};

/*----------------------------------------------------------------------------*/

MOVIERAMA.namespace('MOVIERAMA.globals');
MOVIERAMA.namespace('MOVIERAMA.globals.labels');

/*----------------------------------------------------------------------------*/

MOVIERAMA.namespace('MOVIERAMA.comps');

/*----------------------------------------------------------------------------*/

MOVIERAMA.namespace('MOVIERAMA.ajax.common');
MOVIERAMA.namespace('MOVIERAMA.ajax.config');

MOVIERAMA.ajax.config.url = '';

MOVIERAMA.ajaxCall = function(url, type, inputData, self, processResultFunc) {
  console.log('MOVIERAMA.ajaxCall: URL: [' + url + '] Input Data: ', inputData);
  $.ajax({
    url: url,
    dataType: 'json',
    type: type,
    data: inputData,
    success: function(result) {
      console.log('MOVIERAMA.ajaxCall: RESULT: ', result);
      if(result.ok) {
          processResultFunc(result.data);
        }
        else {
          console.log('MOVIERAMA.ajaxCall: result.errorMsg: [' + result.errorMsg + ']' );
        }
      }.bind(self),
      error: function(xhr, status, err) {
        console.error(url, status, err.toString());
      }.bind(self)
  });
};

MOVIERAMA.ajaxCallWithFiles = function(url, type, inputData, self, processResultFunc) {
  console.log(inputData);
  $.ajax({
    url: url,
    dataType: 'json',
    type: type,
    data: inputData,
    processData: false,
    contentType: false,
    success: function(result) {
    console.log('RESULT: ');
      console.log(result);
      if(result.ok) {
          processResultFunc(result.data);
        }
        else {
          console.log(result.errorMsg);
        }
      }.bind(self),
      error: function(xhr, status, err) {
        console.error(self.props.url, status, err.toString());
      }.bind(self)
  });
};

MOVIERAMA.linkPrefix = function(route)
{
    return MOVIERAMA.globals.assetsURL + route;
}

MOVIERAMA.filePrefix = function(file)
{
    return MOVIERAMA.globals.filesURL + file;
}

MOVIERAMA.imgPrefix = function(fileName, imageSize)
  // '' or F, M, T
{
    var prefix = ( imageSize == 'T' ? 'thumb_' : ( imageSize == 'M' ? 'mid_' : '' ) );
      //console.log( fileName, fileName.substring(1));

    if ( fileName.charAt(0) == '$' ) {
      return MOVIERAMA.globals.systemImgURL + prefix + fileName.substring(1); 
    }
    else {
      return MOVIERAMA.globals.imgURL + prefix + fileName;
    }
}

MOVIERAMA.systemImgPrefix = function(fileName, imageSize)
  // '' or F, M, T)
{
    var prefix = ( imageSize == 'T' ? 'thumb_' : ( imageSize == 'M' ? 'mid_' : '' ) );

    if ( fileName.charAt(0) == '$' ) {
      fileName = fileName.substring(1);
    }
    
    return MOVIERAMA.globals.systemImgURL + prefix + fileName;
}

MOVIERAMA.renderOptions = function (options, whichFieldForValue )
{
  whichFieldForValue = whichFieldForValue || 'value'
  return (
    options.map(function (option)
    {
      return (
        <option name={option.label} value={option[whichFieldForValue]} key={option[whichFieldForValue]}>{option.label}</option>
        );
    })
    );
}

MOVIERAMA.globals.dateF = 'YYYYMMDD000000';
MOVIERAMA.globals.dateTimeF = 'YYYYMMDDhhmmss';

module.exports = MOVIERAMA;
