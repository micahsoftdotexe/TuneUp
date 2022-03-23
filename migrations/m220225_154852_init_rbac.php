<?php

use yii\db\Migration;
use app\models\User;
/**
 * Class m220225_154852_init_rbac
 */
class m220225_154852_init_rbac extends Migration
{
    private function assignSeededUserToRoles()
    {
        $auth = Yii::$app->authManager;
        if (User::findOne(['username' => 'admin'])) {
            $admin = $auth->getRole('admin');
            $auth->assign($admin, User::findOne(['username' => 'admin'])->id);
        }
        if (User::findOne(['username' => 'manager'])) {
            $manager = $auth->getRole('shopManager');
            $auth->assign($manager, User::findOne(['username' => 'manager'])->id);
        }
        if (User::findOne(['username' => 'demo'])) {
            $employee = $auth->getRole('employee');
            $auth->assign($employee, User::findOne(['username' => 'demo'])->id);
        }
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        \Yii::$app->runAction('migrate', ['migrationPath' => '@yii/rbac/migrations/', 'interactive' => false]);
        // create work order permission
        // create customer permission
        // edit customer permission
        // delete customer permission
        // create automobile permission
        // edit automobile permission
        // delete automobile permission
        // create notes permission
        //! edit own notes permission
        //! delete own notes permission
        // delete notes permission
        // create user permission
        // edit user permission
        //! edit own user permission
        // edit user roles permission
        // create labor permission
        // edit labor permission
        // delete labor permission
        // create parts permission
        // edit parts permission
        // delete parts permission
        // view workorder permission


        $auth = Yii::$app->authManager;
        //add rules
        $noteAuthor = new \app\rbac\NoteAuthorRule;
        $auth->add($noteAuthor);
        $userEdit = new \app\rbac\UserEditRule;
        $auth->add($userEdit);

        //permissions
        $createWorkorder = $auth->createPermission('createWorkorder');
        $createWorkorder->description = 'Create Workorder';
        $auth->add($createWorkorder);

        $editWorkorder = $auth->createPermission('editWorkorder');
        $editWorkorder->description = 'Edit Workorder';
        $auth->add($editWorkorder);

        $deleteWorkorder = $auth->createPermission('deleteWorkorder');
        $deleteWorkorder->description = 'Delete Workorder';
        $auth->add($deleteWorkorder);

        $createCustomer = $auth->createPermission('createCustomer');
        $createCustomer->description = 'Create Customer';
        $auth->add($createCustomer);

        $editCustomer = $auth->createPermission('editCustomer');
        $editCustomer->description = 'Edit Customer';
        $auth->add($editCustomer);

        $deleteCustomer = $auth->createPermission('deleteCustomer');
        $deleteCustomer->description = 'Delete Customer';
        $auth->add($deleteCustomer);

        $createAuto = $auth->createPermission('createAuto');
        $createAuto->description = 'Create Automobile';
        $auth->add($createAuto);

        $editAuto = $auth->createPermission('editAuto');
        $editAuto->description = 'Edit Automobile';
        $auth->add($editAuto);

        $deleteAuto = $auth->createPermission('deleteAuto');
        $deleteAuto->description = 'Delete Automobile';
        $auth->add($deleteAuto);

        $createNote = $auth->createPermission('createNote');
        $createNote->description = 'Create Note';
        $auth->add($createNote);

        $editNote = $auth->createPermission('editOwnNote');
        $editNote->description = 'Edit Own Note';
        $editNote->ruleName = $noteAuthor->name;
        $auth->add($editNote);

        $deleteNote = $auth->createPermission('deleteOwnNote');
        $deleteNote->description = 'Delete Own Note';
        $deleteNote->ruleName = $noteAuthor->name;
        $auth->add($deleteNote);

        $deleteNote2 = $auth->createPermission('deleteNote');
        $deleteNote2->description = 'Delete Note';
        $auth->add($deleteNote2);

        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create User';
        $auth->add($createUser);

        $editUser = $auth->createPermission('editOwnUser');
        $editUser->description = 'Edit Own User';
        $editUser->ruleName = $userEdit->name;
        $auth->add($editUser);

        $editUserRoles = $auth->createPermission('editUserRoles');
        $editUserRoles->description = 'Edit User Roles';
        $auth->add($editUserRoles);

        $createLabor = $auth->createPermission('createLabor');
        $createLabor->description = 'Create Labor';
        $auth->add($createLabor);

        $editLabor = $auth->createPermission('editLabor');
        $editLabor->description = 'Edit Labor';
        $auth->add($editLabor);

        $deleteLabor = $auth->createPermission('deleteLabor');
        $deleteLabor->description = 'Delete Labor';
        $auth->add($deleteLabor);

        $createParts = $auth->createPermission('createPart');
        $createParts->description = 'Create Parts';
        $auth->add($createParts);

        $editParts = $auth->createPermission('editPart');
        $editParts->description = 'Edit Parts';
        $auth->add($editParts);

        $deleteParts = $auth->createPermission('deletePart');
        $deleteParts->description = 'Delete Parts';
        $auth->add($deleteParts);

        $viewWorkorder = $auth->createPermission('viewWorkorder');
        $viewWorkorder->description = 'View Workorder';
        $auth->add($viewWorkorder);

        //create employee role
        $employee = $auth->createRole('employee');
        $auth->add($employee);
        $auth->addChild($employee, $editUser);
        $auth->addChild($employee, $createNote);
        $auth->addChild($employee, $editNote);
        $auth->addChild($employee, $deleteNote);
        $auth->addChild($employee, $viewWorkorder);

        //create shop manager role
        $shopManager = $auth->createRole('shopManager');
        $auth->add($shopManager);
        $auth->addChild($shopManager, $employee);
        $auth->addChild($shopManager, $createWorkorder);
        $auth->addChild($shopManager, $editWorkorder);
        $auth->addChild($shopManager, $createAuto);
        $auth->addChild($shopManager, $createCustomer);
        $auth->addChild($shopManager, $editAuto);
        $auth->addChild($shopManager, $editCustomer);

        //create admin role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $shopManager);
        $auth->addChild($admin, $employee);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $editUserRoles);
        $auth->addChild($admin, $deleteWorkorder);

        $this->assignSeededUserToRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $success = false;
        $performLocalDowngrade = true;  // Specify whether to perform downgrade here.  If not here, delegate that to @yii/rbac/migrations migration script to avoid duplication.
        if ($performLocalDowngrade) {
            echo "\nDropping RBAC tables\n";
            \app\helpers\DbHelper::dropRbacTables();

            $success = true;
        } else {
            // Execute Yii RBAC migration @yii/rbac/migrations/m140506_102106_rbac_init
            //\Yii::$app->runAction('migrate/down', ['migrationPath' => '@yii/rbac/migrations/', 'interactive' => false]);

            $success = true;  // Do not perform downgrade here.  Delegate that to @yii/rbac/migrations migration script to avoid duplication.
        }
        return $success;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220225_154852_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
