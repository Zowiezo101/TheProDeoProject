
from src.database.database import DEFAULT_LANG
from src.objects.items import ItemBase

###########################################


class Sources (ItemBase):
    def __init__(self, lang):
        # Set the object base
        self.type = "source"
        ItemBase.__init__(self, self.type)

        # Language used for this class
        self.lang = lang.lower()

        # File name
        self.file_name = "sources"

        # The info on this table
        self.table_name = "sources"
        self.id1_name = "id"
        self.columns = [
            "id",
            "source",
        ]

        # The template used for the file
        self.template = "Source: ({%ID%})\n\t" \
                        "{{%SOURCE%}}\n"

        # Links to different tables
        self.links = {
            "note": {
                "table_name": "note_to_source",
                "id1_name": "source_id",
                "id2_name": "note_id",
                "columns": ["note_id",
                            "source_id"],
                "renames": {"note_capped": "SUBSTRING(n.note, 1, 30) as note_capped",
                            "source_id_group": "GROUP_CONCAT(CONCAT(s.source, ' (', s.id, ')') "
                                               "ORDER BY s.id ASC SEPARATOR ', ') AS source_id_group"},
                "joins": ["LEFT JOIN notes n ON n.id = x2y.note_id",
                          "LEFT JOIN sources s on s.id = x2y.source_id"],
                "groups": {"source_id": "note_id"},
                "template": "{%NOTE_CAPPED%} ({%NOTE_ID%})\n\t"
                            "{{%SOURCE_ID_GROUP%}}\n"
            },
        }

        return

    def write_notes(self):
        if self.lang == DEFAULT_LANG:
            self.write_link("note")
        else:
            print("### note_to_source is not supported in other languages ###")
        return

    def read_notes(self):
        if self.lang == DEFAULT_LANG:
            self.read_link("note")
        else:
            print("### note_to_source is not supported in other languages ###")
        return
