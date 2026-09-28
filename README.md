# Clear duplicate events after Opencart upgrade

After upgrade to OC 4.1.0.4 there are duplicate trigger+action events created, like sending customer email on new order, so they receive the same email twice.
This simple extension clears the newly created duplicate.

# ALWAYS CREATE BACKUP BEFORE INSTALLING AND RUNNING EXTENSIONS

# How to install and run
Go to Extensions > Installer > click on **Upload** > select the **remove_duplicate_events.ocmod.zip**
![alt text](images/image.png)

Click the green **Install** button
![alt text](images/image-1.png)

Go to Extensions > Extensions > select from dropdown **Modules**
![alt text](images/image-2.png)

Find ```Clear Duplicate Events``` > click **Install** > click **Edit**
![alt text](images/image-3.png)

If any duplicate events are found they are displayed in the table, where gray is the newer event and will be removed when ```Clear Duplicates``` button is pressed
![alt text](images/image-4.png)

![alt text](images/image-5.png)