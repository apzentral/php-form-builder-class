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
