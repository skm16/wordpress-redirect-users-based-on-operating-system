// enable support for the  placeholder attribute in INPUT fields at least for Internet Explorer 8+, Firefox and Opera
function supports_input_placeholder() {
    var i = document.createElement('input');
    return 'placeholder' in i;
}

if (!supports_input_placeholder()) {
    var fields = document.getElementsByTagName('INPUT');
    for (var i = 0; i < fields.length; i++) {
        if (fields[i].hasAttribute('placeholder')) {
            fields[i].defaultValue = fields[i].getAttribute('placeholder');
            fields[i].onfocus = function() {
                if (this.value == this.defaultValue) this.value = '';
            }
            fields[i].onblur = function() {
                if (this.value == '') this.value = this.defaultValue;
            }
        }
    }
}

// custom validation message for URL inputs
var elements = document.getElementsByTagName("INPUT");
for (var i = 0; i < elements.length; i++) {
    elements[i].oninvalid = function(e) {
        e.target.setCustomValidity("");
        if (!e.target.validity.valid) {
            e.target.setCustomValidity("Please ented a vadid URL including http:// or https://");
        }
    };
    elements[i].oninput = function(e) {
        e.target.setCustomValidity("");
    };
}
