var Autocomplete = function(el, options) {
    this.el = $(el);
    this.elico = $(el + 'ajaxico');
    this.id = this.el.identify();
    this.el.setAttribute('autocomplete', 'off');
    this.suggestions = [];
    this.data = [];
    this.badQueries = [];
    this.selectedIndex = -1;
    this.currentValue = this.el.value;
    this.intervalId = 0;
    this.cachedResponse = [];
    this.instanceId = null;
    this.onChangeInterval = null;
    this.ignoreValueChange = false;
    this.serviceUrl = options.serviceUrl;
    this.options = {
        autoSubmit: false,
        minChars: 1,
        enableloader: 0,
        maxHeight: 300,
        deferRequestBy: 500,
        width: 0,
        searchtext: '',
        baseUrl: '',
        secureUrl: '',
        container: null
    };
    if (options) {
        Object.extend(this.options, options);
    }
    if (Autocomplete.isDomLoaded) {
        this.initialize();
    } else {
        Event.observe(document, 'dom:loaded', this.initialize.bind(this), false);
    }
};
Autocomplete.instances = [];
Autocomplete.isDomLoaded = false;
Autocomplete.getInstance = function(id) {
    var instances = Autocomplete.instances;
    var i = instances.length;
    while (i--) {
        if (instances[i].id === id) {
            return instances[i];
        }
    }
};
Autocomplete.highlight = function(value, re) {
    return value.replace(re, function(match) {
        return '<strong>' + match + '<\/strong>'
    });
};
Autocomplete.prototype = {
    killerFn: null,
    initialize: function() {
        var me = this;
        this.killerFn = function(e) {
            if (!$(Event.element(e)).up('.autocomplete')) {
                me.killSuggestions();
                me.disableKillerFn();
            }
        }.bindAsEventListener(this);
        if (!this.options.width) {
            this.options.width = this.el.getWidth();
        }
        var div = new Element('div', {
            style: 'position:absolute;'
        });
        div.update('<div class="autocomplete-w1"><div class="autocomplete-w2"><div class="autocomplete" id="Autocomplete_' + this.id + '" style="display:none; width:' + this.options.width + 'px;"></div></div></div>');
        this.options.container = $(this.options.container);
        if (this.options.container) {
            this.options.container.appendChild(div);
            this.fixPosition = function() {};
        } else {
            document.body.appendChild(div);
        }
        this.mainContainerId = div.identify();
        this.container = $('Autocomplete_' + this.id);
        this.fixPosition();
        Event.observe(this.el, window.opera ? 'keypress' : 'keydown', this.onKeyPress.bind(this));
        Event.observe(this.el, 'keyup', this.onKeyUp.bind(this));
        Event.observe(this.el, 'blur', this.enableKillerFn.bind(this));
        Event.observe(this.el, 'focus', this.fixPosition.bind(this));
        Event.observe(this.el, 'click', this.fixText.bind(this));
        Event.observe(this.el, 'blur', this.fixText.bind(this));
        this.container.setStyle({
            maxHeight: this.options.maxHeight + 'px'
        });
        this.instanceId = Autocomplete.instances.push(this) - 1;
    },
    fixPosition: function() {
        var offset = this.el.cumulativeOffset();
        $(this.mainContainerId).setStyle({
            top: (offset.top + this.el.getHeight()) + 'px',
            left: offset.left + 'px'
        });
    },
    fixText: function() {
        if (this.el.value == this.options.searchtext) {
            this.el.value = '';
        } else if (this.el.value.length == 0) {
            this.el.value = this.options.searchtext;
        } else {
            return;
        };
    },
    enableKillerFn: function() {
        Event.observe(document.body, 'click', this.killerFn);
    },
    disableKillerFn: function() {
        Event.stopObserving(document.body, 'click', this.killerFn);
    },
    killSuggestions: function() {
        this.stopKillSuggestions();
        this.intervalId = window.setInterval(function() {
            this.hide();
            this.stopKillSuggestions();
        }.bind(this), 300);
    },
    stopKillSuggestions: function() {
        window.clearInterval(this.intervalId);
    },
    onKeyPress: function(e) {
        if (!this.enabled) {
            return;
        }
        switch (e.keyCode) {
            case Event.KEY_ESC:
                this.el.value = this.currentValue;
                this.hide();
                break;
            case Event.KEY_TAB:
            case Event.KEY_RETURN:
                if (this.selectedIndex === -1) {
                    this.hide();
                    return;
                }
                this.select(this.selectedIndex);
                if (e.keyCode === Event.KEY_TAB) {
                    return;
                }
                break;
            case Event.KEY_UP:
                this.moveUp();
                break;
            case Event.KEY_DOWN:
                this.moveDown();
                break;
            default:
                return;
        }
        Event.stop(e);
    },
    onKeyUp: function(e) {
        switch (e.keyCode) {
            case Event.KEY_UP:
            case Event.KEY_DOWN:
                return;
        }
        clearInterval(this.onChangeInterval);
        if (this.currentValue !== this.el.value) {
            if (this.options.deferRequestBy > 0) {
                this.onChangeInterval = setInterval((function() {
                    this.onValueChange();
                }).bind(this), this.options.deferRequestBy);
            } else {
                this.onValueChange();
            }
        }
    },
    onValueChange: function() {
        clearInterval(this.onChangeInterval);
        this.currentValue = this.el.value;
        this.selectedIndex = -1;
        if (this.ignoreValueChange) {
            this.ignoreValueChange = false;
            return;
        }
        if (this.currentValue === '' || this.currentValue.length < this.options.minChars) {
            this.hide();
        } else {
            this.getSuggestions();
        }
    },
    getSuggestions: function() {
        var cr = this.cachedResponse[this.currentValue];
        if (cr && Object.isArray(cr.suggestions)) {
            this.suggestions = cr.suggestions;
            this.data = cr.data;
            this.suggest();
        } else if (!this.isBadQuery(this.currentValue)) {
            this.showloader();
            var currentUrl = window.location.href;
            var isBaseUrl = (0 === currentUrl.indexOf(this.options.baseUrl));
            var isRequestBaseUrl = (0 === this.serviceUrl.indexOf(this.options.baseUrl));
            if (isBaseUrl && !isRequestBaseUrl) {
                this.serviceUrl = this.serviceUrl.replace(this.options.secureUrl, this.options.baseUrl);
            } else if (!isBaseUrl && isRequestBaseUrl) {
                this.serviceUrl = this.serviceUrl.replace(this.options.baseUrl, this.options.secureUrl);
            }
            new Ajax.Request(this.serviceUrl, {
                parameters: {
                    query: this.currentValue
                },
                onComplete: this.processResponse.bind(this),
                method: 'get'
            });
        }
    },
    isBadQuery: function(q) {
        var i = this.badQueries.length;
        while (i--) {
            if (q.indexOf(this.badQueries[i]) === 0) {
                return true;
            }
        }
        return false;
    },
    hide: function() {
        this.enabled = false;
        this.selectedIndex = -1;
        this.container.hide();
    },
    suggest: function() {
        this.hideloader();
        if (this.suggestions.length === 0) {
            this.hide();
            return;
        }
        var content = [];
        var re = this.currentValue.match(/\w+/g);
        if (re) {
            re = new RegExp('\\b' + re.join('|\\b'), 'gi');
        }
        var previousIsHtml = false,
            i = 0,
            n = 0;
        this.suggestions.each(function(value) {
            if (value.html) {
                n++;
                content.push(value.html);
                previousIsHtml = true;
                return;
            }
            var image = value.image ? '<img class="ajaxsearchimage" src="' + value.image + '" alt="' + value.name + '">' : '';
            var description = value.description ? '<br /><span class="ajaxsearchdescription">' + value.description + '</span>' : ''
            var p = '<p>';
            if (previousIsHtml) {
                previousIsHtml = false;
                p = '<p class="ajaxsearch-small">';
            }
            content.push((this.selectedIndex === i ? '<div class="selected ajaxsearchtitle"' : '<div class="ajaxsearchtitle"'), ' title="', value.name, '" onclick="Autocomplete.instances[', this.instanceId, '].select(', i + n, ');" onmouseover="Autocomplete.instances[', this.instanceId, '].activate(', i, ');">', image, p, re ? Autocomplete.highlight(value.name, re) : value.name, description, '</p>', '</div>');
            i++;
        }.bind(this));
        this.enabled = true;
        this.container.update(content.join('')).show();
    },
    processResponse: function(xhr) {
        var response;
        try {
            response = xhr.responseText.evalJSON();
            if (!Object.isArray(response.data)) {
                response.data = [];
            }
        } catch (err) {
            return;
        }
        this.cachedResponse[response.query] = response;
        if (response.suggestions.length === 0) {
            this.badQueries.push(response.query);
        }
        if (response.query === this.currentValue) {
            this.suggestions = response.suggestions;
            this.data = response.data;
            this.suggest();
        }
    },
    activate: function(index) {
        var divs = $(this.container).select('div');
        var activeItem;
        if (this.selectedIndex !== -1 && divs.length > this.selectedIndex) {
            divs[this.selectedIndex].className = '';
        }
        this.selectedIndex = index;
        if (this.selectedIndex !== -1 && divs.length > this.selectedIndex) {
            activeItem = divs[this.selectedIndex]
            activeItem.className = 'selected';
        }
        return activeItem;
    },
    deactivate: function(div, index) {
        div.className = '';
        if (this.selectedIndex === index) {
            this.selectedIndex = -1;
        }
    },
    select: function(i) {
        var selectedValue = this.suggestions[i].name;
        if (selectedValue) {
            this.el.value = selectedValue;
            if (this.options.autoSubmit && this.el.form) {
                this.el.form.submit();
            }
            this.ignoreValueChange = true;
            this.hide();
            this.onSelect(i);
        }
    },
    moveUp: function() {
        if (this.selectedIndex === -1) {
            return;
        }
        if (this.selectedIndex === 0) {
            $(this.container).select('div')[0].className = '';
            this.selectedIndex = -1;
            this.el.value = this.currentValue;
            return;
        }
        this.adjustScroll(this.selectedIndex - 1);
    },
    moveDown: function() {
        if (this.selectedIndex === (this.suggestions.length - 1)) {
            return;
        }
        this.adjustScroll(this.selectedIndex + 1);
    },
    showloader: function() {
        if (this.options.enableloader == 1) {
            jQuery('#loading').show();
//            this.elico.setStyle({
//                display: 'block'
//            });
        }
    },
    hideloader: function() {
        if (this.options.enableloader == 1) {
            jQuery('#loading').hide();
//            this.elico.setStyle({
//                display: 'none'
//            });
        }
    },
    adjustScroll: function(i) {
        var container = this.container;
        var activeItem = this.activate(i);
        var offsetTop = activeItem.offsetTop;
        var upperBound = container.scrollTop;
        var lowerBound = upperBound + this.options.maxHeight - 25;
        if (offsetTop < upperBound) {
            container.scrollTop = offsetTop;
        } else if (offsetTop > lowerBound) {
            container.scrollTop = offsetTop - this.options.maxHeight + 25;
        }
        this.el.value = this.suggestions[i].name;
    },
    onSelect: function(i) {
        (this.options.onSelect || Prototype.emptyFunction)(this.suggestions[i], this.data[i]);
    }
};
Event.observe(document, 'dom:loaded', function() {
    Autocomplete.isDomLoaded = true;
}, false);
var Autocomplete = function(el, options) {
    this.el = $(el);
    this.elico = $(el + 'ajaxico');
    this.id = this.el.identify();
    this.el.setAttribute('autocomplete', 'off');
    this.suggestions = [];
    this.data = [];
    this.badQueries = [];
    this.selectedIndex = -1;
    this.currentValue = this.el.value;
    this.intervalId = 0;
    this.cachedResponse = [];
    this.instanceId = null;
    this.onChangeInterval = null;
    this.ignoreValueChange = false;
    this.serviceUrl = options.serviceUrl;
    this.options = {
        autoSubmit: false,
        minChars: 1,
        enableloader: 0,
        maxHeight: 300,
        deferRequestBy: 500,
        width: 0,
        searchtext: '',
        baseUrl: '',
        secureUrl: '',
        container: null
    };
    if (options) {
        Object.extend(this.options, options);
    }
    if (Autocomplete.isDomLoaded) {
        this.initialize();
    } else {
        Event.observe(document, 'dom:loaded', this.initialize.bind(this), false);
    }
};
Autocomplete.instances = [];
Autocomplete.isDomLoaded = false;
Autocomplete.getInstance = function(id) {
    var instances = Autocomplete.instances;
    var i = instances.length;
    while (i--) {
        if (instances[i].id === id) {
            return instances[i];
        }
    }
};
Autocomplete.highlight = function(value, re) {
    return value.replace(re, function(match) {
        return '<strong>' + match + '<\/strong>'
    });
};
Autocomplete.prototype = {
    killerFn: null,
    initialize: function() {
        var me = this;
        this.killerFn = function(e) {
            if (!$(Event.element(e)).up('.autocomplete')) {
                me.killSuggestions();
                me.disableKillerFn();
            }
        }.bindAsEventListener(this);
        if (!this.options.width) {
            this.options.width = this.el.getWidth();
        }
        var div = new Element('div', {
            style: 'position:absolute;'
        });
        div.update('<div class="autocomplete-w1"><div class="autocomplete-w2"><div class="autocomplete" id="Autocomplete_' + this.id + '" style="display:none; width:' + this.options.width + 'px;"></div></div></div>');
        this.options.container = $(this.options.container);
        if (this.options.container) {
            this.options.container.appendChild(div);
            this.fixPosition = function() {};
        } else {
            document.body.appendChild(div);
        }
        this.mainContainerId = div.identify();
        this.container = $('Autocomplete_' + this.id);
        this.fixPosition();
        Event.observe(this.el, window.opera ? 'keypress' : 'keydown', this.onKeyPress.bind(this));
        Event.observe(this.el, 'keyup', this.onKeyUp.bind(this));
        Event.observe(this.el, 'blur', this.enableKillerFn.bind(this));
        Event.observe(this.el, 'focus', this.fixPosition.bind(this));
        Event.observe(this.el, 'click', this.fixText.bind(this));
        Event.observe(this.el, 'blur', this.fixText.bind(this));
        this.container.setStyle({
            maxHeight: this.options.maxHeight + 'px'
        });
        this.instanceId = Autocomplete.instances.push(this) - 1;
    },
    fixPosition: function() {
        var offset = this.el.cumulativeOffset();
        $(this.mainContainerId).setStyle({
            top: (offset.top + this.el.getHeight()) + 'px',
            left: offset.left + 'px'
        });
    },
    fixText: function() {
        if (this.el.value == this.options.searchtext) {
            this.el.value = '';
        } else if (this.el.value.length == 0) {
            this.el.value = this.options.searchtext;
        } else {
            return;
        };
    },
    enableKillerFn: function() {
        Event.observe(document.body, 'click', this.killerFn);
    },
    disableKillerFn: function() {
        Event.stopObserving(document.body, 'click', this.killerFn);
    },
    killSuggestions: function() {
        this.stopKillSuggestions();
        this.intervalId = window.setInterval(function() {
            this.hide();
            this.stopKillSuggestions();
        }.bind(this), 300);
    },
    stopKillSuggestions: function() {
        window.clearInterval(this.intervalId);
    },
    onKeyPress: function(e) {
        if (!this.enabled) {
            return;
        }
        switch (e.keyCode) {
            case Event.KEY_ESC:
                this.el.value = this.currentValue;
                this.hide();
                break;
            case Event.KEY_TAB:
            case Event.KEY_RETURN:
                if (this.selectedIndex === -1) {
                    this.hide();
                    return;
                }
                this.select(this.selectedIndex);
                if (e.keyCode === Event.KEY_TAB) {
                    return;
                }
                break;
            case Event.KEY_UP:
                this.moveUp();
                break;
            case Event.KEY_DOWN:
                this.moveDown();
                break;
            default:
                return;
        }
        Event.stop(e);
    },
    onKeyUp: function(e) {
        switch (e.keyCode) {
            case Event.KEY_UP:
            case Event.KEY_DOWN:
                return;
        }
        clearInterval(this.onChangeInterval);
        if (this.currentValue !== this.el.value) {
            if (this.options.deferRequestBy > 0) {
                this.onChangeInterval = setInterval((function() {
                    this.onValueChange();
                }).bind(this), this.options.deferRequestBy);
            } else {
                this.onValueChange();
            }
        }
    },
    onValueChange: function() {
        clearInterval(this.onChangeInterval);
        this.currentValue = this.el.value;
        this.selectedIndex = -1;
        if (this.ignoreValueChange) {
            this.ignoreValueChange = false;
            return;
        }
        if (this.currentValue === '' || this.currentValue.length < this.options.minChars) {
            this.hide();
        } else {
            this.getSuggestions();
        }
    },
    getSuggestions: function() {
        var cr = this.cachedResponse[this.currentValue];
        if (cr && Object.isArray(cr.suggestions)) {
            this.suggestions = cr.suggestions;
            this.data = cr.data;
            this.suggest();
        } else if (!this.isBadQuery(this.currentValue)) {
            this.showloader();
            var currentUrl = window.location.href;
            var isBaseUrl = (0 === currentUrl.indexOf(this.options.baseUrl));
            var isRequestBaseUrl = (0 === this.serviceUrl.indexOf(this.options.baseUrl));
            if (isBaseUrl && !isRequestBaseUrl) {
                this.serviceUrl = this.serviceUrl.replace(this.options.secureUrl, this.options.baseUrl);
            } else if (!isBaseUrl && isRequestBaseUrl) {
                this.serviceUrl = this.serviceUrl.replace(this.options.baseUrl, this.options.secureUrl);
            }
            new Ajax.Request(this.serviceUrl, {
                parameters: {
                    query: this.currentValue
                },
                onComplete: this.processResponse.bind(this),
                method: 'get'
            });
        }
    },
    isBadQuery: function(q) {
        var i = this.badQueries.length;
        while (i--) {
            if (q.indexOf(this.badQueries[i]) === 0) {
                return true;
            }
        }
        return false;
    },
    hide: function() {
        this.enabled = false;
        this.selectedIndex = -1;
        this.container.hide();
    },
    suggest: function() {
        this.hideloader();
        if (this.suggestions.length === 0) {
            this.hide();
            return;
        }
        var content = [];
        var re = this.currentValue.match(/\w+/g);
        if (re) {
            re = new RegExp('\\b' + re.join('|\\b'), 'gi');
        }
        var previousIsHtml = false,
            i = 0,
            n = 0;
        this.suggestions.each(function(value) {
            if (value.html) {
                n++;
                content.push(value.html);
                previousIsHtml = true;
                return;
            }
            var image = value.image ? '<img class="ajaxsearchimage" src="' + value.image + '" alt="' + value.name + '">' : '';
            var description = value.description ? '<br /><span class="ajaxsearchdescription">' + value.description + '</span>' : ''
            var p = '<p>';
            if (previousIsHtml) {
                previousIsHtml = false;
                p = '<p class="ajaxsearch-small">';
            }
            var s = content.push((this.selectedIndex === i ? '<div class="selected ajaxsearchtitle"' : '<div class="ajaxsearchtitle"'), ' title="', value.name, '" onclick="Autocomplete.instances[', this.instanceId, '].select(', i + n, ');" onmouseover="Autocomplete.instances[', this.instanceId, '].activate(', i, ');">', image, p, re ? Autocomplete.highlight(value.name, re) : value.name, description, '</p>', '</div>');
            i++;
        }.bind(this));
        this.enabled = true;
        this.container.update(content.join('')).show();
console.log(s);    
},
    processResponse: function(xhr) {
        var response;
        try {
            response = xhr.responseText.evalJSON();
            if (!Object.isArray(response.data)) {
                response.data = [];
            }
        } catch (err) {
            return;
        }
        this.cachedResponse[response.query] = response;
        if (response.suggestions.length === 0) {
            this.badQueries.push(response.query);
        }
        if (response.query === this.currentValue) {
            this.suggestions = response.suggestions;
            this.data = response.data;
            this.suggest();
        }
    },
    activate: function(index) {
        var divs = $(this.container).select('div');
        var activeItem;
        if (this.selectedIndex !== -1 && divs.length > this.selectedIndex) {
            divs[this.selectedIndex].className = '';
        }
        this.selectedIndex = index;
        if (this.selectedIndex !== -1 && divs.length > this.selectedIndex) {
            activeItem = divs[this.selectedIndex]
            activeItem.className = 'selected';
        }
        return activeItem;
    },
    deactivate: function(div, index) {
        div.className = '';
        if (this.selectedIndex === index) {
            this.selectedIndex = -1;
        }
    },
    select: function(i) {
        var selectedValue = this.suggestions[i].name;
        if (selectedValue) {
            this.el.value = selectedValue;
            if (this.options.autoSubmit && this.el.form) {
                this.el.form.submit();
            }
            this.ignoreValueChange = true;
            this.hide();
            this.onSelect(i);
        }
    },
    moveUp: function() {
        if (this.selectedIndex === -1) {
            return;
        }
        if (this.selectedIndex === 0) {
            $(this.container).select('div')[0].className = '';
            this.selectedIndex = -1;
            this.el.value = this.currentValue;
            return;
        }
        this.adjustScroll(this.selectedIndex - 1);
    },
    moveDown: function() {
        if (this.selectedIndex === (this.suggestions.length - 1)) {
            return;
        }
        this.adjustScroll(this.selectedIndex + 1);
    },
    showloader: function() {
        if (this.options.enableloader == 1) {
              jQuery('#loading').show();  
//            this.elico.setStyle({
//                display: 'block'
//            });
        }
    },
    hideloader: function() {
        if (this.options.enableloader == 1) {
             jQuery('#loading').hide();
//            this.elico.setStyle({
//                display: 'none'
//            });
        }
    },
    adjustScroll: function(i) {
        var container = this.container;
        var activeItem = this.activate(i);
        var offsetTop = activeItem.offsetTop;
        var upperBound = container.scrollTop;
        var lowerBound = upperBound + this.options.maxHeight - 25;
        if (offsetTop < upperBound) {
            container.scrollTop = offsetTop;
        } else if (offsetTop > lowerBound) {
            container.scrollTop = offsetTop - this.options.maxHeight + 25;
        }
        this.el.value = this.suggestions[i].name;
    },
    onSelect: function(i) {
        (this.options.onSelect || Prototype.emptyFunction)(this.suggestions[i], this.data[i]);
    }
};
Event.observe(document, 'dom:loaded', function() {
    Autocomplete.isDomLoaded = true;
}, false);