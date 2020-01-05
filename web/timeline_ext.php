<?php
// Make it easier to copy/paste code or make a new file
if (!isset($id)) {
	$id = "timeline_ext";
}

require_once "layout/layout.php"; 

function timeline_ext_Helper_layout() {
	_Map_Helper_layout();
}
?> 

<script>
// Small list of enums:
var ENUM_UNDEFINED = -1;
var ENUM_SEC = 0;
var ENUM_MIN = 1;
var ENUM_HRS = 2;
var ENUM_DAY = 3;
var ENUM_WKS = 4;
var ENUM_MTH = 5;
var ENUM_YRS = 6;
var ENUM_DEC = 7;
var ENUM_CEN = 8;
var ENUM_MIL = 9;

// The amount needed to go to the "next level"
var MULTS = [60, 	// Seconds
    60,		// Minutes
    24,		// Hours
    7,		// Days
    (52 / 12),		// Weeks
    12,		// Months
    10,		// Years
    10,		// Decades
    10		// Centuries
];


function CreateEvent(name, ID, previousID, length) {
    this.name = name;
    this.ID = ID;
    this.previousID = previousID;
    this.Length = length;
    this.multPrevs = [];

    // Own loop counter to prevent the counters messing each other up
    this.counter = 0;

    // Children of this event
    this.ChildIDs = [];
    this.ChildIndex;

    // Generations from the first ancestor
    this.level = -1;
    // Which event on this level is this
    this.levelIndex = -1;

    // The length variables and types
    this.lengthIndex = -1;
    this.lengthType = -1;

    // Location of this event
    this.Location = [-1, -1];
    this.offset = 0;

    // Make sure this person isn't duplicated
    this.drawn = 0;
    
    this.checkForDuplicates = function () { 
        var matchingItems = getItemsById(this.ID);
        if (matchingItems.length > 1) {
            // This event has multiple objects, meaning that multiple events
            // are linking to this event as their next event
            var firstItem = getItemById(this.ID);
            
            // We already handled this event, this is one of it's duplicates
            if (firstItem.multPrevs.length === 0) {
                for (var i = 0; i < matchingItems.length; i++) {
                    // Link all the previous values to the first of this event
                    firstItem.multPrevs.push(matchingItems[i].previousID);
                }
            }
        }
    };

    /** 
     * @param {Integer} level - TODO */
    this.setLevel = function (level) {
        var IDset = [];

        // alert("setLevel! Level " + level + " for child " + this.name);
        if (level > this.level) {
            // Take the highest level possible
            this.level = level;
        }

        if (this.ChildIDs.length !== 0)
        {
            IDset = this.ChildIDs;
        }

        // This would have been much easier using recursive functions
        // But there is too much recursion for the browser to handle..
        return IDset;
    };

    /** CalcLocation function */
    this.calcLocation = function () {

        this.calcTime();

        // Calculate the Y coordinate
        var X = 25;

        // Calculate the X coordinate
        var Y = 50 + 75;

        // Is this the first person of the family tree?
        if (this.previousID !== -1) {

            // ID number of the parent that will be used
            var id = this.previousID;

            var Parent = getItemById(id);
            var numChildren = Parent.ChildIDs.length;
            var Location_1 = Parent.Location[1];
            
            // If this event has multiple parents, get the average height..
            if (this.multPrevs.length !== 0) {
                var TotalYCoord = 0;
                
                for (var i = 0; i < this.multPrevs.length; i++) {
                    var tempParent = getItemById(this.multPrevs[i]);
                    TotalYCoord += tempParent.Location[1];
                }
                
                var AvgYCoord = TotalYCoord / this.multPrevs.length;
                Location_1 = AvgYCoord;
            }

            // Is it odd or even?
            var odd = numChildren % 2;

            // And which index do we have?
            var Index = this.ChildIndex;

            // Now calculate where our position should be
            if (odd) {
                var middle = ((numChildren + 1) / 2) - 1;

                if (Index === middle) {
                    // Are we in the middle? 
                    // Then just use parents X coordinate
                    Y = Location_1;
                } else if (Index > middle) {
                    // Are we on the right side of the middle?
                    // Place the block on the right side of parents X coordinate
                    var offset = Index - middle;
                    Y = Location_1 + offset*100;
                } else {
                    // Are we on the left side of the middle?
                    // Place the block on the left side of parents X coordinate
                    var offset = middle - Index;
                    Y = Location_1 - offset*100;
                }
            } else {
                var middle = numChildren / 2;
                if (Index >= middle) {
                    // Are we on the right side of the middle?
                    // Place the block on the right side of parents X coordinate
                    var offset = Index - middle;
                    Y = (Location_1 + (100 / 2)) + offset*100;
                } else {
                    // Are we on the left side of the middle?
                    // Place the block on the left side of parents X coordinate
                    var offset = middle - Index;
                    Y = (Location_1 + (100 / 2)) - offset*100;
                }
            }

            X = Parent.Location[0] + Parent.lengthIndex*100 + 50;
        }

        // This value is used, in case someone is overlapping with someone else
        this.Location[0] = X;
        this.Location[1] = Y + this.offset;

        return;
    };

    this.getAncestors = function () {
        var ListOfIDs = [];

        if (this.previousID === -1) {
            if (this.ChildIDs.length === 0) {
                // We do not have a family tree for this person..
            } else {
                // We are ancestors
                ListOfIDs = [ItemsList.indexOf(this.ID)];
            }
        } else {
            // We must have ancestors
            // The set of people to work with
            var IDset = [this.ID];

            // This breaks the while loop
            var done = 0;

            while (done === 0)
            {				
                var newIDset = [];
                for (i = 0; i < IDset.length; i++) {
                    var Item = getItemById(IDset[i]);

                    // Create the ID set of the next generation
                    if (Item.previousID !== -1) {
                        newIDset.push(Item.previousID);
                    } else {
                        // This is an ancestor
                        var AncestorID = ItemsList.indexOf(Item.ID);
                        ListOfIDs.push(AncestorID);
                    }
                }

                // There are no more children to update
                IDset = uniq(newIDset);
                if (IDset.length === 0) {
                    done = 1;
                }
            }
        }

        return uniq(ListOfIDs);
    };

    /**
     * @param {Integer} lengthType - Numerical value to define the color used for the event-item */
    this.getTimeColor = function (lengthType) {
        var color = '';
        switch(lengthType) {
            case ENUM_SEC:
            color = "Green";
            break;

            case ENUM_MIN:
            color = "LightBlue";
            break;

            case ENUM_HRS:
            color = "Yellow";
            break;

            case ENUM_DAY:
            color = "Red";
            break;

            case ENUM_WKS:
            color = "Purple";
            break;

            case ENUM_MTH:
            color = "White";
            break;

            case ENUM_YRS:
            color = "LightGrey";
            break;

            case ENUM_DEC:
            color = "Violet";
            break;

            case ENUM_CEN:
            color = "Orange";
            break;

            case ENUM_MIL:
            color = "DarkBlue";
            break;

            default:
            color = "grey";
            break;
        }
        return color;
    };

    this.StringToValue = function (lengthTypeStr) {

        switch(lengthTypeStr) {
            case 's':
            lengthType = ENUM_SEC;
            break;

            case 'i':
            lengthType = ENUM_MIN;
            break;

            case 'h':
            lengthType = ENUM_HRS;
            break;

            case 'd':
            lengthType = ENUM_DAY;
            break;

            case 'w':
            lengthType = ENUM_WKS;
            break;

            case 'm':
            lengthType = ENUM_MTH;
            break;

            case 'y':
            lengthType = ENUM_YRS;
            break;

            case 'D':
            lengthType = ENUM_DEC;
            break;

            case 'C':
            lengthType = ENUM_CEN;
            break;

            case 'M':
            lengthType = ENUM_MIL;
            break;

            default:
            lengthType = ENUM_UNDEFINED;
            break;
        }

        return lengthType;
    };

    this.StringToType = function (lengthTypeStr, Length) {

        switch(lengthTypeStr) {
            case 's':
            lengthType = "<?php echo $dict_Timeline["second"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["seconds"]; ?>";
            }
            break;

            case 'i':
            lengthType = "<?php echo $dict_Timeline["minute"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["minutes"]; ?>";
            }
            break;

            case 'h':
            lengthType = "<?php echo $dict_Timeline["hour"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["hours"]; ?>";
            }
            break;

            case 'd':
            lengthType = "<?php echo $dict_Timeline["day"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["days"]; ?>";
            }
            break;

            case 'w':
            lengthType = "<?php echo $dict_Timeline["week"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["weeks"]; ?>";
            }
            break;

            case 'm':
            lengthType = "<?php echo $dict_Timeline["month"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["months"]; ?>";
            }
            break;

            case 'y':
            lengthType = "<?php echo $dict_Timeline["year"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["years"]; ?>";
            }
            break;

            case 'D':
            lengthType = "<?php echo $dict_Timeline["decade"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["decades"]; ?>";
            }
            break;

            case 'C':
            lengthType = "<?php echo $dict_Timeline["century"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["centuries"]; ?>";
            }
            break;

            case 'M':
            lengthType = "<?php echo $dict_Timeline["millennium"]; ?>";
            if (Length !== 1) {
                lengthType = "<?php echo $dict_Timeline["millennia"]; ?>";
            }
            break;

            default:
            lengthType = "<?php echo $dict_Timeline["unknown"]; ?>";
            break;
        }

        return lengthType;
    };

    this.convertType = function (value, fromType, toType) {
        // This function assumes that the value input, 
        // do not cause a value that is smaller than 1
        var typeLoop = 0;
        var newValue = value;
        if (fromType > toType) {
            typeLoop = fromType - toType;

            // Convert from bigger type to a smaller type
            for (var loop = 0; loop < typeLoop; loop++) {
                newValue = value*MULTS[toType + loop];
            }
        } else if (fromType < toType) {
            typeLoop = toType - fromType;

            // Convert from smaller type to a bigger type
            for (var loop = 0; loop < typeLoop; loop++) {
                newValue = (value / MULTS[fromType + loop]);
            }
        }

        return newValue;
    };

    this.convertLength = function (value, Type) {
        // This function calculates what the length of a block should be
        // This is from 0+ to 10, 0+ for the shortest length and 10 for the longest
        var newValue = 2;
        if ((Type < ENUM_MIL) && (Type > ENUM_UNDEFINED)) {
            newValue = (value / MULTS[Type])*10;
        } else if (Type === ENUM_MIL) {
            newValue = value;
        }

        return newValue;
    };

    this.convertString = function (value) {
        // This function converts the cryptic values to a readable string
        var newValue = "";
        if (value === "") {
            newValue = "<?php echo $dict_Timeline["unknown"]; ?>";
        } else {
            // Convert every time type
            var timeParts = this.Length.split(" ");

            for (var types = 0; types < timeParts.length; types++) {
                var currentTypeStr = timeParts[types];
                var currentTypeStrLen = currentTypeStr.length;

                var currentStr = currentTypeStr.slice(currentTypeStrLen - 1, currentTypeStrLen);
                var currentLen = parseInt(currentTypeStr.slice(0, currentTypeStrLen - 1));

                var currentType = this.StringToType(currentStr, currentLen);

                newValue += currentLen + " " + currentType;
                if (types < (timeParts.length - 1)) {
                    newValue += ", ";
                }
            }
        }

        return newValue;
    };

    this.convertText = function (Text, value) {
        var svgns = "http://www.w3.org/2000/svg";

        // The second tSpan gets an additional offset
        firstTSPAN = 0;

        if (this.Length !== "-1") {
            // The tspan containing the time length
            var tSpan = document.createElementNS(svgns, "tspan");	
            tSpan.RectID = this.ID;

            // Update the contents of the current tspan object
            tSpan.setAttributeNS(null,  "x", this.Location[0] + 5);
            tSpan.setAttributeNS(null, "dy", -10);
            tSpan.textContent = this.convertString(this.Length);

            Text.appendChild(tSpan);
            // The second tSpan gets an additional offset
            firstTSPAN = 1;
        }

        var subLength = Math.round(11 * this.lengthIndex);
        var subStart = 0;
        var subString = "";

        do {
            var tSpan = document.createElementNS(svgns, "tspan");	
            tSpan.RectID = this.ID;

            // Get the string that we put into the tspan object
            subString = value.substr(subStart, subLength);

            // Increment our string offset
            subStart += subLength;

            // Update the contents of the current tspan object
            tSpan.setAttributeNS(null,  "x", this.Location[0] + 5);
            tSpan.setAttributeNS(null, "dy", 15 + 10*firstTSPAN);
            firstTSPAN = 0;

            if ((subString.length === subLength) && (value[subStart] !== " ") && (value[subStart - 1] !== " ") && (value.length !== subStart) ){
                tSpan.textContent = (subString + "-");
            } else {
                tSpan.textContent = (subString);
            }

            Text.appendChild(tSpan);
        } while (subString.length === subLength);

        return;
    };

    this.calcTime = function () {
        var timeParts = this.Length.split(" ");

        // Default defines
        var lengthTypeStr = "";
        var lengthType = ENUM_UNDEFINED;
        var Length = this.Length;

        if ((timeParts.length > 0) && (timeParts[0] !== "")) {
            // Clear before we start calculating
            Length = 0;

            // Find the smallest timepart and convert the bigger timeparts to the same level
            // The code down here will try to find the biggest possible level of the sum 
            var minType = ENUM_MIL;
            for (var types = 0; types < timeParts.length; types++) {
                // Get the time type (last char of the string)
                var currentTypeStr = timeParts[types];
                var currentTypeStrLen = currentTypeStr.length;

                var TypeStr = currentTypeStr.slice(currentTypeStrLen - 1, currentTypeStrLen);
                var Type = this.StringToValue(TypeStr);

                // Looping for the smallest type
                if (Type < minType) {
                    minType = Type;
                    // alert("Minimum type: " + minType);
                }
            }


            // Now that we have the smallest type, we can start converting!
            for (var types = 0; types < timeParts.length; types++) {
                var currentTypeStr = timeParts[types];
                var currentTypeStrLen = currentTypeStr.length;

                var currentStr = currentTypeStr.slice(currentTypeStrLen - 1, currentTypeStrLen);
                var currentType = this.StringToValue(currentStr);

                var currentLen = parseInt(currentTypeStr.slice(0, currentTypeStrLen - 1));
                // alert("Type: " + currentType + "\nMin type: " + minType);
                var addLength = this.convertType(currentLen, currentType, minType);
                Length += addLength;
                // alert("Length: " + Length + " and added Length: " + addLength);
            }

            lengthType = minType;

            // Can we get a higher level length type?
            if (lengthType !== ENUM_UNDEFINED) {
                // While loop here, getting a higher level untill a value is smaller than zero
                var newLength = 0;
                while (true) {
                    newLength = this.convertType(Length, lengthType, lengthType + 1);
                    // alert("Old length: " + Length + "\nNew length: " + newLength + "\nType: " + lengthType);

                    if ((newLength < 1) || (lengthType === ENUM_MIL)){
                        break;
                    }

                    lengthType++;
                    Length = newLength;
                }
            }
        }

        this.lengthIndex = this.convertLength(Length, lengthType);
        this.lengthType = lengthType;

        // Update the global width value
        globalWidth += (this.lengthIndex*100);
    };

    /** */
    this.drawEvent = function() {
        var svgns = "http://www.w3.org/2000/svg";
        var hrefns = "http://www.w3.org/1999/xlink";
        var Group = document.createElementNS(svgns, "g");

        // Move everything away from the upper border
        var x = this.Location[0];
        var y = this.Location[1] + globalOffset;
        
        // This object has multiple parents, draw them all
        if(this.multPrevs.length > 0) {
            for (var i = 0; i < this.multPrevs.length; i++) {
                // Draw the lines to the mother, to the middle of the bottom
                var Parent = getItemById(this.multPrevs[i]);

                // And only if the parents are drawn as well
                if ((Parent.Location[0] !== -1) && (Parent.Location[1] !== -1)) {
                    var x_parent = Parent.Location[0] + Parent.lengthIndex*100;
                    var y_parent = Parent.Location[1] + 25 + globalOffset;

                    // Make three lines, to get nice 90 degree angles
                    var LineMother1 = document.createElementNS(svgns, "line");
                    var LineMother2 = document.createElementNS(svgns, "line");
                    var LineMother3 = document.createElementNS(svgns, "line");

                    var x_halfway1 = x_parent + (50 / 2);
                    var x_halfway2 = x - (50 / 2);

                    // The first line goes only vertical, and halfway
                    LineMother1.setAttributeNS(null, 'x1', x_parent);
                    LineMother1.setAttributeNS(null, 'y1', y_parent);
                    LineMother1.setAttributeNS(null, 'x2', x_halfway1);
                    LineMother1.setAttributeNS(null, 'y2', y_parent);

                    // The second line goes only horizontal, or diagonal
                    LineMother2.setAttributeNS(null, 'x1', x_halfway1);
                    LineMother2.setAttributeNS(null, 'y1', y_parent);
                    LineMother2.setAttributeNS(null, 'x2', x_halfway2);
                    LineMother2.setAttributeNS(null, 'y2', y + 25);

                    // The last line goes only vertical, the second half
                    LineMother3.setAttributeNS(null, 'x1', x_halfway2);
                    LineMother3.setAttributeNS(null, 'y1', y + 25);
                    LineMother3.setAttributeNS(null, 'x2', x);
                    LineMother3.setAttributeNS(null, 'y2', y + 25);

                    if (this.level === (Parent.level + 1)) {
                        LineMother1.setAttributeNS(null, 'stroke', 'pink');
                        LineMother2.setAttributeNS(null, 'stroke', 'pink');
                        LineMother3.setAttributeNS(null, 'stroke', 'pink');

                        LineMother1.setAttributeNS(null, 'stroke-width', '5');
                        LineMother2.setAttributeNS(null, 'stroke-width', '5');
                        LineMother3.setAttributeNS(null, 'stroke-width', '5');
                    } else {
                        LineMother1.setAttributeNS(null, 'stroke', 'deeppink');
                        LineMother2.setAttributeNS(null, 'stroke', 'deeppink');
                        LineMother3.setAttributeNS(null, 'stroke', 'deeppink');

                        LineMother1.setAttributeNS(null, 'stroke-width', '2');
                        LineMother2.setAttributeNS(null, 'stroke-width', '2');
                        LineMother3.setAttributeNS(null, 'stroke-width', '2');
                    }

                    Group.appendChild(LineMother1);
                    Group.appendChild(LineMother2);
                    Group.appendChild(LineMother3);
                }
            }
        } else if ((this.previousID !== -1) && (this.previousID !== "")) {
            // Draw the lines to the mother, to the middle of the bottom
            var Parent = getItemById(this.previousID);

            // And only if the parents are drawn as well
            if ((Parent.Location[0] !== -1) && (Parent.Location[1] !== -1)) {
                var x_parent = Parent.Location[0] + Parent.lengthIndex*100;
                var y_parent = Parent.Location[1] + 25 + globalOffset;

                // Make three lines, to get nice 90 degree angles
                var LineMother1 = document.createElementNS(svgns, "line");
                var LineMother2 = document.createElementNS(svgns, "line");
                var LineMother3 = document.createElementNS(svgns, "line");

                var x_halfway1 = x_parent + (50 / 2);
                var x_halfway2 = x - (50 / 2);

                // The first line goes only vertical, and halfway
                LineMother1.setAttributeNS(null, 'x1', x_parent);
                LineMother1.setAttributeNS(null, 'y1', y_parent);
                LineMother1.setAttributeNS(null, 'x2', x_halfway1);
                LineMother1.setAttributeNS(null, 'y2', y_parent);

                // The second line goes only horizontal, or diagonal
                LineMother2.setAttributeNS(null, 'x1', x_halfway1);
                LineMother2.setAttributeNS(null, 'y1', y_parent);
                LineMother2.setAttributeNS(null, 'x2', x_halfway2);
                LineMother2.setAttributeNS(null, 'y2', y + 25);

                // The last line goes only vertical, the second half
                LineMother3.setAttributeNS(null, 'x1', x_halfway2);
                LineMother3.setAttributeNS(null, 'y1', y + 25);
                LineMother3.setAttributeNS(null, 'x2', x);
                LineMother3.setAttributeNS(null, 'y2', y + 25);

                if (this.level === (Parent.level + 1)) {
                    LineMother1.setAttributeNS(null, 'stroke', 'pink');
                    LineMother2.setAttributeNS(null, 'stroke', 'pink');
                    LineMother3.setAttributeNS(null, 'stroke', 'pink');

                    LineMother1.setAttributeNS(null, 'stroke-width', '5');
                    LineMother2.setAttributeNS(null, 'stroke-width', '5');
                    LineMother3.setAttributeNS(null, 'stroke-width', '5');
                } else {
                    LineMother1.setAttributeNS(null, 'stroke', 'deeppink');
                    LineMother2.setAttributeNS(null, 'stroke', 'deeppink');
                    LineMother3.setAttributeNS(null, 'stroke', 'deeppink');

                    LineMother1.setAttributeNS(null, 'stroke-width', '2');
                    LineMother2.setAttributeNS(null, 'stroke-width', '2');
                    LineMother3.setAttributeNS(null, 'stroke-width', '2');
                }

                Group.appendChild(LineMother1);
                Group.appendChild(LineMother2);
                Group.appendChild(LineMother3);
            }
        }

        var Rect = document.createElementNS(svgns, "rect");		
        Rect.setAttributeNS(null, 'width', this.lengthIndex*100);
        Rect.setAttributeNS(null, 'height', 50);

        Rect.setAttributeNS(null, 'x', x);
        Rect.setAttributeNS(null, 'y', y);

        Rect.setAttributeNS(null, 'rx', 5);
        Rect.setAttributeNS(null, 'ry', 5);

        Rect.setAttributeNS(null, 'stroke', 'black');
        Rect.setAttributeNS(null, 'fill', this.getTimeColor(this.lengthType));

        Rect.id = "Rect" + this.ID;		
        Rect.RectID = this.ID;

        var Text = document.createElementNS(svgns, "text");		
        Text.setAttributeNS(null, 'width', this.lengthIndex*100);
        Text.setAttributeNS(null, 'height', 50);

        Text.setAttributeNS(null, 'x', x);
        Text.setAttributeNS(null, 'y', y);

        this.convertText(Text, this.name);
        Text.RectID = this.ID;

        var newHref = updateURLParameter("events.php", "id", this.ID);
        var Link = document.createElementNS(svgns, "a");
        Link.setAttributeNS(hrefns, 'xlink:href', newHref);
        Link.setAttributeNS(hrefns, 'xlink:title', '<?php echo $dict_Timeline["link_event"]; ?>');
        Link.setAttributeNS(hrefns, 'target', "_top");

        Link.appendChild(Rect);
        Link.appendChild(Text);

        Link.RectID = this.ID;
        Link.setAttributeNS(null, 'onmouseover', 'setBorder(evt)');
        Link.setAttributeNS(null, 'onmouseout',  'clearBorder(evt)');

        Group.appendChild(Link);		
        return Group;
    };

    /**
     * @param {SVGElement} SVG - TODO */
    this.drawTimeLine = function(SVG) {	
        var IDset = [];

        var Group = this.drawEvent();
        if ((Group !== null) && (this.drawn === 0)) {
            SVG.appendChild(Group);
            this.drawn = 1;
        }

        if (this.ChildIDs.length !== 0)
        {
            IDset = this.ChildIDs;
        }

        // This would have been much easier using recursive functions
        // But there is too much recursion for the browser to handle..
        return IDset;
    };
}


function setItems() {	
    // Create all connections
    for (i = 0; i < Items.length; i++) {
        var Item = Items[i];
        Item.checkForDuplicates();

        if (Item.previousID > -1) {            
            var Parent = getItemById(Item.previousID);
            Item.ChildIndex = Parent.ChildIDs.length;
            Parent.ChildIDs.push(Item.ID);
        }
    }

    // TODO: This can be simply done in PHP using SQL
    for (i = 0; i < Items.length; i++) {
        var Item = Items[i];
        if ((Item.previousID === -1) && (Item.ChildIDs.length > 0)) {
            // This event is at the beginning of a time line
            ItemsList.push(Item.ID);
        }
    }
}

/** 
* @param {Integer} firstID - TODO
* @param {Integer} highestLevel - TODO */
function calcLocations(firstID, highestLevel) {
	
	// This breaks the while loop
	var done = 0;
	var MaxLevel = levelCounter.length;
	// var MaxLevel = 38;
	var debugFrom = 900;
	
	while (done === 0)
	{
		var collision = 0;
		globalWidth = 0;
		// alert("Start while loop");
		
		// Draw the tree per level
		for (level = 0; level < MaxLevel; level++) {
			
			// The IDs of the people of the current level
			var IDset = levelIDs[level];
			
			for (i = 0; i < IDset.length; i++) {
				var Item = getItemById(IDset[i]);
				Item.calcLocation();
				
				// To get the width, keep the highest Y coordinate we can find
				if (Item.Location[1] > globalHeight) {
					globalHeight = Item.Location[1];
				}
				
				// Do a check on the location of the person
				if (Item.Location[1] < (50 + 75)) {
					// Person seems to fall out of boundary
					// What offset do we need?
					var offset = (50 + 75) - Item.Location[1];
					
					if (offset > globalOffset) {
						// Take the highest offset found
						globalOffset = offset;
					}
				}
				
				if ((Item.level > 0) && (Item.levelIndex > 0)) {
					// alert("Name: " + Person.name + "\nIndex: " + Person.ID + "\nLevel: " + Person.level + "\nLevelIndex: " + Person.levelIndex);
					// alert("set of IDs of level " + Person.level + " :" + IDset);
					
					// Find the neighbour.
					// This is the person who has the same level, but levelIndex - 1
					var idNeighbour = IDset[Item.levelIndex - 1];
					// alert("ID of Neighbour: " + idNeighbour);
					var Neighbour = getItemById(idNeighbour);
					
					// alert("Neighbour Name: " + Neighbour.name + "\nLevel: " + Neighbour.level + "\nLevelIndex: " + Neighbour.levelIndex);
					
					// If we get in the if function, these two people are overlapping.
					// Or the lower person is too far up and needs to move down
					if (Item.Location[1] < (Neighbour.Location[1] + 100)) {
						// alert("set of IDs of level " + Person.level + " :" + IDset);
						if (Item.level >= debugFrom) {
						// alert("Is people " + Item.name + " on location (" + Item.Location[0] + ", " + Item.Location[1] + ") overlapping with neighbour " + Neighbour.name + " on location (" + Neighbour.Location[0] + ", " + Neighbour.Location[1] + ")?");
						// alert("Idx of " + Item.name + " :" + Item.levelIndex);
						// alert("Idx of " + Neighbour.name + " :" + Neighbour.levelIndex);
						}
						
						var found = 0;
						var FoundID = -1;
						
						// Us
						var currentAncestorsR = [Item.ID];
						
						// The neighbour
						var currentAncestorsL = [Neighbour.ID];
						
						// Our starting level
						var currentLevel = Item.level;
						
						// found = 1;
						while (found === 0) {
							// Get a list with people that are a generation level lower (placed higher)
							if (Item.level >= debugFrom) {
							// alert("Trying level: " + currentLevel);
							}
							var currentIDset = levelIDs[currentLevel];
							if (Item.level >= debugFrom) {
							// alert("Set of people: " + currentIDset);
							}
							var currentIDset = levelIDs[currentLevel - 1];
							if (Item.level >= debugFrom) {
							// alert("Set of people: " + currentIDset);
							}
							var newAncestorsR = [];
							var newAncestorsL = [];
							
							// Find all the possible ancestors for the right person
							for (var j = 0; j < currentAncestorsR.length; j++) {
								var ItemR = getItemById(currentAncestorsR[j]);
								if (Item.level >= debugFrom) {
								// alert("Working with " + ItemR.name);
								}
								
								for (var k = 0; k < currentIDset.length; k++) {
									var ID = currentIDset[k];
									
									// Remember the list of ancestors that we find for this person
									if (ID === ItemR.previousID) {
										newAncestorsR.push(ID);
										if (Item.level >= debugFrom) {
										// alert("Found parentR with ID: " + ID);
										}
									}
								}
							}
							
							// Find all the possible ancestors for the left person
							for (var j = 0; j < currentAncestorsL.length; j++) {
								var ItemL = getItemById(currentAncestorsL[j]);
								if (Item.level >= debugFrom) {
								// alert("Working with " + ItemL.name);
								}
								
								for (var k = 0; k < currentIDset.length; k++) {
									var ID = currentIDset[k];
									
									// Remember the list of ancestors that we find for this person
									if (ID === ItemL.previousID) {
										newAncestorsL.push(ID);
										if (Item.level >= debugFrom) {
										// alert("Found parentL with ID: " + ID);
										}
									}
								}
							}
							
							// Now check if we have a match on this level!
							var count = 0;
							for (var j = 0; j < newAncestorsR.length; j++) {
								var RightID = newAncestorsR[j];
								for (var k = 0; k < newAncestorsL.length; k++) {
									var LeftID = newAncestorsL[k];
									
									// We have found a match!
									// This is the ancestor that connects to two colliding people
									if (RightID === LeftID) {
										FoundID = RightID;
										found = 1;
										count++;
									}
								}
							}
							
							if (Item.level >= debugFrom) {
							// alert("Count is equal to: " + count);
							}
							
							// collision = 1;
							if (found === 0) {
								// Keep the current data if we have a match
								currentAncestorsR = newAncestorsR;
								currentAncestorsL = newAncestorsL;
								currentLevel--;
							}
							
							if (currentLevel < 0) {
								// Couldn't find the parent?
								// alert("Could not find the connecting parent?");
								break;
							}
						}
						
						var Parent = getItemById(FoundID);
						var Child = null;
						if (Item.level >= debugFrom) {
						// alert("The connecting parent is: " + Parent.name + " with ID: " + Parent.ID);
						}							
						
						// This is just a normal clash, fix in the normal way
						for (var k = currentAncestorsR.length; k > 0; k--) {
							var ID = currentAncestorsR[k - 1];
							
							for (var j = 0; j < Parent.ChildIDs.length; j++) {
								var ChildID = Parent.ChildIDs[j];

								if (ID === ChildID) {
									// Find the child that needs to be moved
									Child = getItemById(ID);
								}
							}
						}
							
						Child.offset += (Neighbour.Location[1] + 100) - Item.Location[1];
						if (Item.level >= debugFrom) {
						// alert("The child is: " + Child.name + " with ID: " + Child.ID + " and moved with: " + Child.offset);
						}
						collision = 1;
						
						if (collision === 1) {
							// alert("Breaking first for-loop");
							break;
						}
					}
					
					// Update IDset to [ID] (start all over again)
					// newIDset = [ID];
					// break;
				}
			}
			
			if (collision === 1) {
				// Break out of the loop and start again
				// alert("Breaking second for-loop");
				break;
			}
		}
		
		// There are no more children to update
		if (level === MaxLevel) {
			done = 1;
		}
	}
	
	return;
}
	
/** 
* @param {SVGElement} SVG - TODO */
function drawTimeLine(SVG) {	
	
	// This breaks the while loop
	var done = 0;
	var MaxLevel = levelCounter.length;
	
	while (done === 0)
	{		
		// Draw the timeline per level
		for (level = 0; level < MaxLevel; level++) {
			
			var IDset = levelIDs[level];
			
			for (var i = 0; i < IDset.length; i++) {
				var Item = getItemById(IDset[i]);
				Item.drawTimeLine(SVG);
			}
		}
		
		// There are no more children to update
		if (level === MaxLevel) {
			done = 1;
		}
	}
	
	return;
}

/***/
function panItem(item) {
	var TimeLine = document.getElementById("timeline_ext_div");
	scrollTop = (item.Location[1] + globalOffset + 50) - (TimeLine.offsetHeight / 2);
	scrollLeft = (item.Location[0] + 75) - (TimeLine.offsetWidth / 2);
	
	updateViewbox(-scrollLeft, -scrollTop, -1);
}

function prep_appendGroup() {
	var svgns = "http://www.w3.org/2000/svg";
	var SVG = document.getElementById("svg");
	
	var Group = document.createElementNS(svgns, "g");	
	Group.id = "timeline_ext_svg";
	
	SVG.appendChild(Group);
	UpdateProgress(45);
	
	setTimeout(prep_DrawLegenda, 1);
}

function prep_DrawLegenda() {
	//Legenda
	var svgns = "http://www.w3.org/2000/svg";
	var SVG = document.getElementById("svg");
	
	var Item = getItemById(globalItemId);
	
	var Group = document.createElementNS(svgns, "g");	
	Group.id = "Legenda";
	
	var LegendaStr = ['s', 'i', 'h', 'd', 'w', 'm', 'y', 'D', 'C', 'M', 'a'];
	for (var i = 0; i < (MULTS.length + 2); i++) {
		var Rect = document.createElementNS(svgns, "rect");		
		Rect.setAttributeNS(null, 'width', 10);
		Rect.setAttributeNS(null, 'height', 10);
		Rect.setAttributeNS(null, 'x', 15 + (100*Math.floor(i / 5)));
		Rect.setAttributeNS(null, 'y', 15*((i % 5) + 1));
		Rect.setAttributeNS(null, 'stroke', 'black');
		Rect.setAttributeNS(null, 'fill', Item.getTimeColor(i));
		
		var Text = document.createElementNS(svgns, "text");		
		Text.setAttributeNS(null, 'x', 30 + (100*Math.floor(i / 5)));
		Text.setAttributeNS(null, 'y', 15*((i % 5) + 1) + 10);
		Text.textContent = Item.StringToType(LegendaStr[i], 0);
		
		Group.appendChild(Rect);
		Group.appendChild(Text);
	}
	SVG.appendChild(Group);
	UpdateProgress(55);
	
	setTimeout(prep_AddControlButtons, 1);
}

function prep_DrawMap() {
	// The TimeLine div
	var TimeLine = document.getElementById("timeline_ext_div");
	var SVG = document.getElementById("svg");
	var Group = document.getElementById("timeline_ext_svg");
	
	// Set the height and the width Plus x pixel border
	ActualWidth = globalWidth + (highestLevel + 1)*50;
	ActualHeight = globalHeight + globalOffset + 75;
	
	SVG.setAttribute('width', TimeLine.offsetWidth);
	SVG.setAttribute('height', TimeLine.offsetHeight);
	
	// Draw the current timeline
	drawTimeLine(Group);
	UpdateProgress(75);
		
	setTimeout(prep_SetInterrupts, 1);
}

</script>