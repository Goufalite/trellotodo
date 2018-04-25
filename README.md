# Trellotodo

Trellotodo is a simple PHP website allowing to quickly delete cards on [Trello](https://trello.com)

- Manage TODO lists
- Manage shopping lists with "checked" elements
- Quickly delete done cards in projects

## Installation
 
- Clone this repo in your website's filesystem.
- Add a `.htaccess` file or manage the view permissions on your folder.
- Open `config.inc.php` and re-define the KEY constant.

## First run

- [Create a trello key and token](https://developers.trello.com/page/authorization)
- Navigate to the `index.php` of this website
- You will be asked to provide trello key and token, enter it with this format : 
```
key=KEY&token=TOKEN
```
- This string will be encrypted and saved as a cookie
- If you want to reset the cookie, navigate to `index.php?deletecookie` of this website

## GUI

- **First combobox** : all your boards
- **Second combobox** : the lists in the selected board
- **Textbox** : add your cards here and press Enter
- **X All** : remove all shown cards
- **X Checked** : remove all "checked" cards
- **[R]** : refresh
- **Checkbox** : enable "checked" mode
- **Link** : share the board, list and checked mode

When in "checked" mode, the click/touch on a card will turn it gray and put it at the bottom instead of deleting it.
Blue cards are cards that were just created at the moment.

## Roadmap (when I'll have the time...)

- **Cache boards and lists** => I'm thinking of a way to force a full refresh in the GUI, this might be time consuming since I have to query all the boards
- **Sort cards per date**
- **Favicon** => since this is a Trello derivated product, I don't want to copy Trello's graphical content...