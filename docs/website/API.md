The website consists of a lot of information that is loaded from the database. This chapter will not go into detail on how the API and database work, but will explain the different API calls you can make to read the database.

## Blogs
Blogs are a bit special, because it's the only type of data that can be added, updated and deleted using the API. This is done to keep the database information safe from any unwanted or unintended harm, while still being able to update the homepage with newer blog entries. The blog API calls will not be explained in this chapter.

## Items
Use the following calls to retrieve information for a single item, where [ID] corresponds to the item ID:
- `GET /api/book/[ID]`
- `GET /api/event/[ID]`
- `GET /api/people/[ID]`
- `GET /api/location/[ID]`
- `GET /api/special/[ID]`

Use the following calls to retrieve all items from a type (books, events, persons, locations or items):
- `GET /api/book/all`
- `GET /api/event/all`
- `GET /api/people/all`
- `GET /api/location/all`
- `GET /api/special/all`

## Maps
Use the following calls to retrieve information for a single map, where [ID] corresponds to the map ID:
- `GET /api/familytree/[ID]` (Map ID is the ID of the **first** parent in a family tree)
- `GET /api/timeline/[ID]` (Map ID is the ID of the timeline)

Use this call to get a list of family trees this person is a part of, where [ID] corresponds to the item ID:
- `Get /api/people/[ID]/maps`

Use the following calls to retrieve all maps from a time (family trees, timelines, worldmap locations):
- `GET /api/familytree/all`
- `GET /api/timeline/all`
- `GET /api/worldmap/all`



