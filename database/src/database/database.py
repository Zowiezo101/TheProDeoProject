
import os

import subprocess
import mysql.connector
from mysql.connector import errorcode

from src.database.database_insert import DatabaseInsert
from src.database.database_empty import DatabaseEmpty
from src.database.database_get import DatabaseGet
from src.database.database_copy import DatabaseCopy
from src.database.database_merge import DatabaseMerge

DEFAULT_LANG = "nl"


# Connection with the MySQL database
class Database(DatabaseInsert, DatabaseEmpty, DatabaseGet, DatabaseCopy, DatabaseMerge):

    ACTION_NEW = 0
    ACTION_DELETE = 1
    ACTION_COPY = 2
    ACTION_MERGE = 3
    ACTION_UPDATE = 4

    def __init__(self, item_base):
        DatabaseInsert.__init__(self, self)
        DatabaseEmpty.__init__(self, self)
        DatabaseGet.__init__(self, self)
        DatabaseCopy.__init__(self, self)
        DatabaseMerge.__init__(self, self)

        self.conn = mysql.connector

        self.override_ids = False
        self.item_base = item_base
        return
        
    def connect_database(self):
        conn = None

        # Connect to the database
        try:
            conn = self.conn.connect(host=os.environ["MYSQL_HOST"],
                                     user=os.environ["MYSQL_USER"],
                                     passwd=os.environ["MYSQL_PASSWORD"],
                                     db="bible")
        except self.conn.Error as err:
            if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
                # Couldn't log in..
                print("Incorrect password or username")
            elif err.errno == errorcode.ER_BAD_DB_ERROR:
                # No database, create it from scratch
                conn = self.init_database()
            else:
                print(err)
                
        return conn
        
    def init_database(self):
        # This database doesn't exist yet, initialize it for use
        conn = self.conn.connect(host=os.environ["MYSQL_HOST"],
                                 user=os.environ["MYSQL_USER"],
                                 passwd=os.environ["MYSQL_PASSWORD"])
        cursor = conn.cursor()
        
        # We have a backup in the SQL folder
        file = open("database/bible.sql")
        sql = file.read()
        print("Initializing database")
        
        # Read the SQL file and import it
        for result in cursor.execute(sql, multi=True):
            print("Numbers of Row affected by statement '{}': {}".format(result.statement, result.rowcount))
        
        # Close the cursor again
        cursor.close()
        
        return conn
    
    def export_database(self):
        print("Exporting database to sql/bible.sql")

        # Dump the contents of the database into sql/bible.sql
        subprocess.check_output(f'mysqldump --host={os.environ["MYSQL_HOST"]} --port=3306 --default-character-set=utf8mb4 '
                                f'--user={os.environ["MYSQL_USER"]} -p{os.environ["MYSQL_PASSWORD"]} --protocol=tcp --no-tablespaces --skip-triggers "bible" '
                                '> sql/bible.sql', shell=True).decode("utf-8")

        # Open the created file
        file = open(r"sql/bible.sql", "r+", encoding='utf-8')

        # Add some extra lines to the content
        sql = file.read()
        sql = """CREATE DATABASE  IF NOT EXISTS `bible` 
/*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ 
/*!80016 DEFAULT ENCRYPTION='N' */;
USE `bible`;

""" + sql
        
        # Overwrite the content with added lines
        file.seek(0)
        file.write(sql)
        file.truncate()
        file.close()

        print("Export finished")
        return
    
    def import_database(self):
        print("Importing database from sql/bible.sql")
        
        # Dump the contents of sql/bible.sql into the database
        subprocess.check_output(f'mysql --host={os.environ["MYSQL_HOST"]} --port=3306 --default-character-set=utf8mb4 '
                                f'--user={os.environ["MYSQL_USER"]} -p{os.environ["MYSQL_PASSWORD"]} "bible" '
                                '< sql/bible.sql', shell=True).decode("utf-8")

        print("Import finished")
        return

    def execute_get(self, sql):
        # Connect to the database
        conn = self.connect_database()

        # Execute the query
        cursor = conn.cursor()
        cursor.execute(sql)
        results = cursor.fetchall()

        conn.close()

        # Get the results
        return results

    def execute_set(self, sql):
        # Connect to the database
        conn = self.connect_database()

        # Execute the query
        cursor = conn.cursor()
        cursor.execute(sql)
        conn.commit()
        conn.close()
        return

    #######################################################
    # Execute action on databases
    #######################################################

    def exec_action(self, action,
                    item_id, item_data):

        if action == self.ACTION_NEW:
            # Insert new ID
            item_id = self.insert_item(
                None,
                item_data,
            )
        elif action == self.ACTION_DELETE:
            # Delete ID
            self.empty_items(item_id)
        elif action == self.ACTION_COPY:
            # Copy ID
            self.copy_item(
                item_id,
                item_data
            )
        elif action == self.ACTION_MERGE:
            self.merge_links(item_id)
        else:
            # Update ID
            self.insert_item(
                item_id,
                item_data
            )

        return item_id

    def get_type(self):
        # Return the item type
        return self.item_base.item_type

    def get_type_id(self):
        # Return the type ID
        return self.item_base.get_type_id("type_item", self.get_type())

    def get_type_names(self, table):
        # Return the type name
        sql = f"SELECT type_id, type_name FROM {table} ORDER BY type_id ASC"
        return self.execute_get(sql)

    def get_table_name(self):
        return self.item_base.table_name

    def get_order_name(self):
        return self.item_base.order_name

    def get_id1_name(self):
        return self.item_base.id1_name

    def get_id2_name(self):
        return self.item_base.id2_name

    def get_columns(self):
        columns = self.item_base.columns
        extra_columns = self.item_base.extra_columns
        sql_columns = self.item_base.sql_columns
        sql_group = self.item_base.sql_group
        
        # Remove columns that are grouped
        for group in sql_group.keys() if sql_group else []:
            columns.remove(group)

        # Return the columns as a string
        columns = ", ".join(columns + extra_columns)

        if sql_columns is not None:
            # Remove columns that are grouped
            for group in sql_group.keys() if sql_group else []:
                sql_columns.remove(group)
            
            # When there are columns with "AS" to give a different name
            columns = ", ".join(sql_columns)

        return columns

    def get_joins(self):
        sql_join = self.item_base.sql_join

        joins = ""
        if sql_join is not None:
            joins = " " + " ".join(sql_join)

        if "%LANG%" in joins:
            joins = joins.replace("%LANG%", self.item_base.lang)

        if "%E_ID%" in joins:
            joins = joins.replace("%E_ID%", self.item_base.event_id)

        return joins

    def get_groups(self):
        sql_group = self.item_base.sql_group

        # Return the groups as a string
        groups = ""

        if sql_group is not None:
            # Return the columns as a string
            groups = " GROUP BY " + ", ".join(sql_group.values())

        return groups

    def get_links(self):
        links = self.item_base.links.keys()
        return links

    def shift_order(self, order_id):
        table_name = self.get_table_name()
        order_name = self.get_order_name()

        # SQL query to get the current order IDs
        get_sql = f"SELECT {order_name} FROM {table_name} " \
                  f"WHERE {order_name} > {order_id} ORDER BY {order_name} DESC;"

        # Get the results
        order_ids = self.execute_get(get_sql)

        # SQL queries to increment the order IDs with 1
        for order_id in order_ids:
            order_id = order_id[0]
            sql = f"UPDATE {table_name} set {order_name} = {order_id} + 1 " \
                  f"WHERE {order_name} = {order_id} LIMIT 1"

            self.execute_set(sql)
        return

    def is_default_lang(self):
        return self.item_base.lang == DEFAULT_LANG
