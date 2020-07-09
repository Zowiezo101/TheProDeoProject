/* global dict_Timeline, Items, prep_AddControlButtons, globalItemId, highestLevel, MapList, levelCounter, levelIDs, updateSessionSettings, session_settings, globalMapId, prep_SetInterrupts */

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

var ENUM_LETTERS = {
    "s": ENUM_SEC,
    "i": ENUM_MIN,
    "h": ENUM_HRS,
    "d": ENUM_DAY,
    "w": ENUM_WKS,
    "m": ENUM_MTH,
    "y": ENUM_YRS,
    "D": ENUM_DEC,
    "C": ENUM_CEN,
    "M": ENUM_MIL
};

var ENUM_STRINGS = {
    "s": [dict_Timeline["second"], dict_Timeline["seconds"]],
    "i": [dict_Timeline["minute"], dict_Timeline["minutes"]],
    "h": [dict_Timeline["hour"], dict_Timeline["hours"]],
    "d": [dict_Timeline["day"], dict_Timeline["days"]],
    "w": [dict_Timeline["week"], dict_Timeline["weeks"]],
    "m": [dict_Timeline["month"], dict_Timeline["months"]],
    "y": [dict_Timeline["year"], dict_Timeline["years"]],
    "D": [dict_Timeline["decade"], dict_Timeline["decades"]],
    "C": [dict_Timeline["century"], dict_Timeline["centuries"]],
    "M": [dict_Timeline["millennium"], dict_Timeline["millennia"]]
};

// The amount needed to go to the "next level"
var MULTS = [
    60,         // Seconds
    60,         // Minutes
    24,         // Hours
    7,          // Days
    (52 / 12),  // Weeks
    12,         // Months
    10,         // Years
    10,         // Decades
    10          // Centuries
];

var DEPTH = 0;
var WIDTH = 1;

var DEPTH_SIZE = 100;
var WIDTH_SIZE = 50;

var DEPTH_OFFSET = 50;
var WIDTH_OFFSET = 50;

getText = function (Text, value) {
    var svgns = "http://www.w3.org/2000/svg";

    // The second tSpan gets an additional offset
    var firstTSPAN = 0;

    if ((this.length !== "-1") && (this.length !== "")) {
        // The tspan containing the time length
        var tSpan = document.createElementNS(svgns, "tspan");    
        tSpan.RectID = this.id;

        // Update the contents of the current tspan object
        tSpan.setAttributeNS(null,  "x", this.Location[0] + 5);
        tSpan.setAttributeNS(null, "dy", -10);
        tSpan.textContent = this.convertString(this.length);

        Text.appendChild(tSpan);
        // The second tSpan gets an additional offset
        firstTSPAN = 1;
    }

    var subLength = Math.round(11 * this.lengthIndex);
    var subStart = 0;
    var subString = "";

    do {
        var tSpan = document.createElementNS(svgns, "tspan");    
        tSpan.RectID = this.id;

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
    
/** 
* @param {Integer} firstID
* @param {Integer} highestLevel */
function calcLocations(firstID, highestLevel) {
    
    // This breaks the while loop
    var done = 0;
    var MaxLevel = levelCounter.length;
    
    while (done === 0)
    {
        // Is there any collision between two people?
        var collision = 0;
        
        // The offset that is needed to get all people of that tree in the SVG.
        //  We need to move the SVG up with this value
        globalOffset = 0;
        
        // Draw the tree per level
        for (var level = 0; level < MaxLevel; level++) {
            
            // The IDs of the people of the current level
            var IDset = levelIDs[level];
            
            for (var i = 0; i < IDset.length; i++) {
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
                    // Find the neighbour.
                    // This is the person who has the same level, but levelIndex - 1
                    var idNeighbour = IDset[Item.levelIndex - 1];
                    var Neighbour = getItemById(idNeighbour);
                    
                    // If we get in the if function, these two people are overlapping.
                    // Or the lower person is too far up and needs to move down
                    if (Item.Location[1] < (Neighbour.Location[1] + 100)) {
                        // Searching for the shared ancestor that these two people have
                        var found = 0;
                        var FoundID = -1;
                        
                        // Us
                        var currentAncestorsR = [Item.id];
                        
                        // The neighbour
                        var currentAncestorsL = [Neighbour.id];
                        
                        // Our starting level
                        var currentLevel = Item.level;
                        
                        while (found === 0) {
                            // Get a list with people that are a generation level lower (placed higher)
                            var currentIDset = levelIDs[currentLevel - 1];
                            
                            var newAncestorsR = [];
                            var newAncestorsL = [];
                            
                            // Find all the possible ancestors for the right person
                            for (var j = 0; j < currentAncestorsR.length; j++) {
                                var ItemR = getItemById(currentAncestorsR[j]);
                                
                                for (var k = 0; k < currentIDset.length; k++) {
                                    var ID = currentIDset[k];
                                    
                                    // Get all the previous IDs (in case there is more than one)
                                    var previousIDs = [];
                                    if (ItemR.parents.length !== -1) {
                                        for (var l = 0; l < ItemR.parents.length; l++) {
                                            previousIDs.push(ItemR.parents[l]);
                                        }
                                    }
                                    
                                    // Remember the list of ancestors that we find for this person
                                    for (var l = 0; l < previousIDs.length; l++) {
                                        if (ID === previousIDs[l]) {
                                            newAncestorsR.push(ID);
                                        }
                                    }
                                }
                            }
                            
                            // Find all the possible ancestors for the left person
                            for (var j = 0; j < currentAncestorsL.length; j++) {
                                var ItemL = getItemById(currentAncestorsL[j]);
                                
                                for (var k = 0; k < currentIDset.length; k++) {
                                    var ID = currentIDset[k];
                                    
                                    // Get all the previous IDs (in case there is more than one)
                                    var previousIDs = [];
                                    if (ItemL.parents.length !== -1) {
                                        for (var l = 0; l < ItemL.parents.length; l++) {
                                            previousIDs.push(ItemL.parents[l]);
                                        }
                                    }
                                    
                                    // Remember the list of ancestors that we find for this person
                                    for (var l = 0; l < previousIDs.length; l++) {   
                                        // Remember the list of ancestors that we find for this person
                                        if (ID === previousIDs[l]) {
                                            newAncestorsL.push(ID);
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
                                    // This is the ancestor that connects the two colliding people
                                    if (RightID === LeftID) {
                                        FoundID = RightID;
                                        found = 1;
                                        count++;
                                    }
                                }
                            }
                            
                            if (found === 0) {
                                // Keep the current data if we have a match
                                currentAncestorsR = newAncestorsR;
                                currentAncestorsL = newAncestorsL;
                                currentLevel--;
                            }
                            
                            if (currentLevel < 0) {
                                // Couldn't find the parent?
                                alert("Could not find the connecting parent?");
                                break;
                            }
                        }
                        
                        var Parent = getItemById(FoundID);
                        var Child = null;                          
                        
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
 * @param {Integer} id */
function getItemColor (id, lengthType) {
    if (typeof lengthType === "undefined") {
        var item = getItemById(id);
        lengthType = item.lengthType;
    }
    
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

function StringToValue (lengthStr) {
    
    if (ENUM_LETTERS.hasOwnProperty(lengthStr)) {
        var lengthType = ENUM_LETTERS[lengthStr];
    } else {
        var lengthType = ENUM_UNDEFINED;
    }

    return lengthType;
};

function StringToType (lengthStr, Length) {
    // 0 (Single) or 1 (Mult)
    Length = (Length > 1) ? 1 : 0;
    
    if (ENUM_STRINGS.hasOwnProperty(lengthStr)) {
        var lengthType = ENUM_STRINGS[lengthStr][Length];
    } else {
        var lengthType = dict_Timeline["unknown"];
    }

    return lengthType;
};

function convertType (value, fromType, toType) {
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

function convertLength (value, Type) {
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

function convertString (id, value) {
    var Item = getItemById(id);
    
    // This function converts the cryptic values to a readable string
    var newValue = "";
    if (value === "") {
        newValue = dict_Timeline["unknown"];
    } else {
        // Convert every time type
        var timeParts = Item.length.split(" ");

        for (var types = 0; types < timeParts.length; types++) {
            var currentTypeStr = timeParts[types];
            var currentTypeStrLen = currentTypeStr.length;

            var currentStr = currentTypeStr.slice(currentTypeStrLen - 1, currentTypeStrLen);
            var currentLen = parseInt(currentTypeStr.slice(0, currentTypeStrLen - 1));

            var currentType = StringToType(currentStr, currentLen);

            newValue += currentLen + " " + currentType;
            if (types < (timeParts.length - 1)) {
                newValue += ", ";
            }
        }
    }

    return newValue;
};

function calcTime (id) {
    var Item = getItemById(id);
    var timeParts = Item.length.split(" ");

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
            var Type = StringToValue(TypeStr);

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
            var currentType = StringToValue(currentStr);

            var currentLen = parseInt(currentTypeStr.slice(0, currentTypeStrLen - 1));
            // alert("Type: " + currentType + "\nMin type: " + minType);
            var addLength = convertType(currentLen, currentType, minType);
            Length += addLength;
            // alert("Length: " + Length + " and added Length: " + addLength);
        }

        lengthType = minType;

        // Can we get a higher level length type?
        if (lengthType !== ENUM_UNDEFINED) {
            // While loop here, getting a higher level untill a value is smaller than zero
            var newLength = 0;
            while (true) {
                newLength = convertType(Length, lengthType, lengthType + 1);
                // alert("Old length: " + Length + "\nNew length: " + newLength + "\nType: " + lengthType);

                if ((newLength < 1) || (lengthType === ENUM_MIL)){
                    break;
                }

                lengthType++;
                Length = newLength;
            }
        }
    }

    Item.lengthIndex = convertLength(Length, lengthType);
    Item.lengthType = lengthType;

    // Update the global width value
    globalWidth += (Item.lengthIndex*100);
};