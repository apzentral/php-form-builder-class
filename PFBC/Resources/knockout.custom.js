//===== KO Custom Handlers =====//

// From http://www.knockmeout.net/
// Function to set uniqueID for id, for, and name
ko.bindingHandlers.uniqueId = {
    init: function(element, valueAccessor) {
        var value = valueAccessor();
        value.id = (value.id || ko.bindingHandlers.uniqueId.prefix) + (ko.bindingHandlers.uniqueId.counter);

        element.id = value.id;
        if (value.setName) {
            element.name = value.id;
        }
        if (value.incCounter) {
            ko.bindingHandlers.uniqueId.counter++;
        }
    },
    counter: 0,
    prefix: "ko_unique_"
};

ko.bindingHandlers.uniqueFor = {
    init: function(element, valueAccessor) {
        var value = valueAccessor();
        value.id = (value.id || ko.bindingHandlers.uniqueId.prefix) + (ko.bindingHandlers.uniqueId.counter);

        element.setAttribute("for", value.id);
        if (value.incCounter) {
            ko.bindingHandlers.uniqueId.counter++;
        }
    }
};

ko.bindingHandlers.uniqueDiv = {
    init: function(element, valueAccessor) {
        var value = valueAccessor();
        value.id = (value.id || ko.bindingHandlers.uniqueId.prefix) + (ko.bindingHandlers.uniqueId.counter);

        element.id = value.id;
        if (value.incCounter) {
            ko.bindingHandlers.uniqueId.counter++;
        }
    }
};

/*===== Require jQuery =====*/
ko.bindingHandlers.slideVisible = {
    update: function(element, valueAccessor, allBindingsAccessor) {
        // First get the latest data that we're bound to
        var value = valueAccessor(), allBindings = allBindingsAccessor();

        // Next, whether or not the supplied model property is observable, get its current value
        var valueUnwrapped = ko.utils.unwrapObservable(value);

        // Grab some more data from another binding property
        var duration = allBindings.slideDuration || 500; // 500ms is default duration unless otherwise specified

        // Now manipulate the DOM element
        if (valueUnwrapped == true)
            jQuery(element).slideDown(duration); // Make the element visible
        else
            jQuery(element).slideUp(duration);   // Make the element invisible
    }
};

ko.bindingHandlers.fadeVisible = {
    update: function(element, valueAccessor, allBindingsAccessor) {
        // First get the latest data that we're bound to
        var value = valueAccessor(), allBindings = allBindingsAccessor();

        // Next, whether or not the supplied model property is observable, get its current value
        var valueUnwrapped = ko.utils.unwrapObservable(value);

        // Grab some more data from another binding property
        var duration = allBindings.slideDuration || 500; // 500ms is default duration unless otherwise specified

        // Now manipulate the DOM element
        if (valueUnwrapped == true)
            jQuery(element).fadeIn(duration); // Make the element visible
        else
            jQuery(element).fadeOut(duration);   // Make the element invisible
    }
};

/*===== Display Yes No =====*/
ko.bindingHandlers.textBoolean = {
    update: function(element, valueAccessor, allBindingsAccessor) {
        var value = valueAccessor();
        value = (value === '1') ? 'Yes' : 'No';
        jQuery(element).text(value);
    }
};