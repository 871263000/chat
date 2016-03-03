// JavaScript Document
	var dbName = "omsdb"; //数据库名称
	var dbVersion = 2.0; //数据库版本
	var tablename = "chatMes"; //表名

		//定义一个IndexDB方法集合对象
	var H5AppDB = {};

	//实例化IndexDB数据上下文，这边根据浏览器类型来做选择
	var indexedDB = window.indexedDB || window.webkitIndexedDB ||window.mozIndexedDB || window.msIndexedDB;

	if ('webkitIndexedDB' in window) {  
	   window.indexedDB = window.webkitIndexedDB;  
	   window.IDBTransaction = window.webkitIDBTransaction;  
	} else if ('mozIndexedDB' in window) {  
	   window.indexedDB = window.mozIndexedDB;  
	} 
	H5AppDB.indexedDB = {};
	// H5AppDB.indexedDB.db = null;
	//错误信息，打印日志
	H5AppDB.indexedDB.onerror = function (e) {
		log.debug(e);
	};
	H5AppDB.indexedDB.open = function () {
		//初始IndexDB
		var request = indexedDB.open(dbName, dbVersion);
				//如果版本不一致，执行版本升级的操作
		request.onupgradeneeded = function (e) {
			H5AppDB.indexedDB.db = e.target.result;
			var db = H5AppDB.indexedDB.db;
			if (!db.objectStoreNames.contains(tablename)) {

				var store = db.createObjectStore(tablename, { keyPath: "id" , autoIncrement: true} );//NoSQL类型数据库中必须的主键，唯一性
			}
		}
		request.onsuccess = function (e) {
			// Old api: var v = "2-beta"; 
			console.log("success to open DB: " + dbName);
			H5AppDB.indexedDB.db = e.target.result;
			var db = H5AppDB.indexedDB.db;
			if (db.objectStoreNames.contains(tablename)) {
				H5AppDB.indexedDB.getAllTodoItems();
				// db.deleteObjectStore(tablename);
			}
		}
		request.onfailure = H5AppDB.indexedDB.onerror;
	};
		//、获取对象信息
	H5AppDB.indexedDB.getAllTodoItems = function () {

	// var todos = document.getElementById("todoItems");
		// todos.innerHTML = "";
		var db = H5AppDB.indexedDB.db;
		var trans = db.transaction([tablename], "readwrite");//通过事物开启对象
		var store = trans.objectStore(tablename);//获取到对象的值

		// Get everything in the store;

		var keyRange = IDBKeyRange.lowerBound(0);
		var cursorRequest = store.openCursor(keyRange);//开启索引为0的表
		$('.con-tab-content .list-group').html('');
		cursorRequest.onsuccess = function (e) {
		var result = e.target.result;
		if (!!result == false)
		return;
		renderTodo(result.value);
		result.continue();//这边执行轮询读取
		};
		cursorRequest.onerror = H5AppDB.indexedDB.onerror;
	};
	H5AppDB.indexedDB.selectData = function (data) {
		var db = H5AppDB.indexedDB.db;
		var trans = db.transaction([tablename], "readwrite");//通过事物开启对象
		var store = trans.objectStore(tablename);
		if (data.db_id == undefined) {
			H5AppDB.indexedDB.addTodo(data);
			return ;
		};
		var request = store.get(parseInt(data.db_id));
		request.onsuccess = function (e){
			if (e.target.result == undefined) {
				H5AppDB.indexedDB.addTodo(data);
			} else {
				H5AppDB.indexedDB.deleteTodo(parseInt(data.db_id))
				H5AppDB.indexedDB.addTodo(data);
			}

		}

	}
	//绑定数据
	function renderTodo(row) {
		if (row.mestype == 'message') {
			$('.con-tab-content .list-group').prepend('<li class="recent-contact chat_people recent-hover" group-name="'+row.group_name+'" session_no="'+row.session_no+'" db_id="'+row.id+'" mes_id="'+row.mes_id+'" mestype ="'+row.mestype+'" ><span class="header-img"><img src="'+row.to_uid_header_img+'" alt=""></span><i>'+row.group_name+'</i><span title = "删除聊天记录" mestype="'+row.mestype+'" db_id="'+row.id+'" session="'+row.session_no+'" class="recent-close">&times;</span></div></li>')
		} else {
			$('.con-tab-content .list-group').prepend('<li class="session_no recent-hover" group-name="'+row.group_name+'" session_no="'+row.session_no+'" db_id="'+row.id+'"mes_id="'+row.mes_id+'" mestype ="'+row.mestype+'" ><div><span class="header-img"><img src="'+row.to_uid_header_img+'" alt=""></span><i>'+row.group_name+'</i><span title = "删除聊天记录" mestype="'+row.mestype+'" db_id="'+row.id+'" session="'+row.session_no+'" class="recent-close">&times;</span></div></li>')
		}
	};
	//4、添加数据对象
	H5AppDB.indexedDB.addTodo = function (data) {
		var db = H5AppDB.indexedDB.db;
		var trans = db.transaction([tablename], "readwrite");//通过事物开启对象
		var store = trans.objectStore(tablename);

		//数据以对象形式保存，体现NoSQL类型数据库的灵活性
		// var data = {
		// 	"mestype": 'mesage',
		// 	"session_no" : '4-6',
		// 	"group-name": 'zdl',
		// 	"mes_id": 1,
		// 	"groupid": 2,
		// 	"text": todoText,
		// 	"timeStamp": new Date().getTime(),
		// };

		var request = store.put(data);//保存数据

		request.onsuccess = function (e) {
			H5AppDB.indexedDB.getAllTodoItems();
		};

		request.onerror = function (e) {
			log.debug("Error Adding: ", e);
		};
	}; 
	function addTodo() {
		var todo = document.getElementById("todo");
		H5AppDB.indexedDB.addTodo(todo.value);
		todo.value = "";
	}
	// 删除数据对象
	H5AppDB.indexedDB.deleteTodo = function(id) {
		var db = H5AppDB.indexedDB.db;
		var trans = db.transaction([tablename], "readwrite");
		var store = trans.objectStore(tablename);
		var request = store.delete(parseInt(id));//根据主键来删除

		request.onsuccess = function(e) {
			// H5AppDB.indexedDB.getAllTodoItems();
		};
		request.onerror = function(e) {
			log.debug("Error Adding: ", e);
		};
	};