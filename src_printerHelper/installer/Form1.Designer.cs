namespace installer
{
    partial class Form1
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Form1));
            this.cancelBtn = new System.Windows.Forms.Button();
            this.nextBtn = new System.Windows.Forms.Button();
            this.closeBtn = new System.Windows.Forms.Button();
            this.panel1 = new System.Windows.Forms.Panel();
            this.label2 = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.createShortcut = new System.Windows.Forms.CheckBox();
            this.installLog = new System.Windows.Forms.TextBox();
            this.logUpdater = new System.Windows.Forms.Timer(this.components);
            this.installPathLabel = new System.Windows.Forms.Label();
            this.installPathTxt = new System.Windows.Forms.TextBox();
            this.doneLabel = new System.Windows.Forms.Label();
            this.startupChk = new System.Windows.Forms.CheckBox();
            this.panel1.SuspendLayout();
            this.SuspendLayout();
            // 
            // cancelBtn
            // 
            this.cancelBtn.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.cancelBtn.Location = new System.Drawing.Point(429, 314);
            this.cancelBtn.Name = "cancelBtn";
            this.cancelBtn.Size = new System.Drawing.Size(75, 23);
            this.cancelBtn.TabIndex = 0;
            this.cancelBtn.Text = "&Cancel";
            this.cancelBtn.UseVisualStyleBackColor = true;
            this.cancelBtn.Click += new System.EventHandler(this.button1_Click);
            // 
            // nextBtn
            // 
            this.nextBtn.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.nextBtn.Location = new System.Drawing.Point(338, 314);
            this.nextBtn.Name = "nextBtn";
            this.nextBtn.Size = new System.Drawing.Size(75, 23);
            this.nextBtn.TabIndex = 0;
            this.nextBtn.Text = "&Next >";
            this.nextBtn.UseVisualStyleBackColor = true;
            this.nextBtn.Click += new System.EventHandler(this.nextBtn_Click);
            // 
            // closeBtn
            // 
            this.closeBtn.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.closeBtn.Enabled = false;
            this.closeBtn.Location = new System.Drawing.Point(257, 314);
            this.closeBtn.Name = "closeBtn";
            this.closeBtn.Size = new System.Drawing.Size(75, 23);
            this.closeBtn.TabIndex = 0;
            this.closeBtn.Text = "< &Back";
            this.closeBtn.UseVisualStyleBackColor = true;
            // 
            // panel1
            // 
            this.panel1.BackColor = System.Drawing.Color.White;
            this.panel1.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
            this.panel1.Controls.Add(this.label2);
            this.panel1.Controls.Add(this.label1);
            this.panel1.Dock = System.Windows.Forms.DockStyle.Top;
            this.panel1.Location = new System.Drawing.Point(0, 0);
            this.panel1.Name = "panel1";
            this.panel1.Size = new System.Drawing.Size(516, 79);
            this.panel1.TabIndex = 1;
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(18, 28);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(261, 26);
            this.label2.TabIndex = 3;
            this.label2.Text = "This wizard installs the checkout printer help software\r\nso that the cash registe" +
    "r can print receipts with speed.";
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label1.Location = new System.Drawing.Point(10, 7);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(150, 13);
            this.label1.TabIndex = 2;
            this.label1.Text = "CashRegister print helper";
            // 
            // createShortcut
            // 
            this.createShortcut.AutoSize = true;
            this.createShortcut.Enabled = false;
            this.createShortcut.Location = new System.Drawing.Point(48, 95);
            this.createShortcut.Name = "createShortcut";
            this.createShortcut.Size = new System.Drawing.Size(154, 17);
            this.createShortcut.TabIndex = 2;
            this.createShortcut.Text = "Create shortcut on desktop";
            this.createShortcut.UseVisualStyleBackColor = true;
            // 
            // installLog
            // 
            this.installLog.BackColor = System.Drawing.Color.White;
            this.installLog.Location = new System.Drawing.Point(23, 95);
            this.installLog.Multiline = true;
            this.installLog.Name = "installLog";
            this.installLog.ReadOnly = true;
            this.installLog.Size = new System.Drawing.Size(481, 213);
            this.installLog.TabIndex = 3;
            this.installLog.Visible = false;
            // 
            // logUpdater
            // 
            this.logUpdater.Tick += new System.EventHandler(this.logUpdater_Tick);
            // 
            // installPathLabel
            // 
            this.installPathLabel.AutoSize = true;
            this.installPathLabel.Location = new System.Drawing.Point(45, 95);
            this.installPathLabel.Name = "installPathLabel";
            this.installPathLabel.Size = new System.Drawing.Size(61, 13);
            this.installPathLabel.TabIndex = 4;
            this.installPathLabel.Text = "Install path:";
            this.installPathLabel.Visible = false;
            // 
            // installPathTxt
            // 
            this.installPathTxt.Location = new System.Drawing.Point(48, 111);
            this.installPathTxt.Name = "installPathTxt";
            this.installPathTxt.Size = new System.Drawing.Size(400, 20);
            this.installPathTxt.TabIndex = 5;
            this.installPathTxt.Text = "C:\\Program Files (x86)\\CashRegister_PrintHelper";
            this.installPathTxt.Visible = false;
            // 
            // doneLabel
            // 
            this.doneLabel.AutoSize = true;
            this.doneLabel.Location = new System.Drawing.Point(45, 95);
            this.doneLabel.Name = "doneLabel";
            this.doneLabel.Size = new System.Drawing.Size(275, 39);
            this.doneLabel.TabIndex = 6;
            this.doneLabel.Text = "Installation has been completed.\r\n\r\nThank you for chosing M.C. van Leeuwen Cash R" +
    "egister\r\n";
            this.doneLabel.Visible = false;
            // 
            // startupChk
            // 
            this.startupChk.AutoSize = true;
            this.startupChk.Checked = true;
            this.startupChk.CheckState = System.Windows.Forms.CheckState.Checked;
            this.startupChk.Location = new System.Drawing.Point(48, 114);
            this.startupChk.Name = "startupChk";
            this.startupChk.Size = new System.Drawing.Size(141, 17);
            this.startupChk.TabIndex = 7;
            this.startupChk.Text = "Launch helper at startup";
            this.startupChk.UseVisualStyleBackColor = true;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(516, 349);
            this.Controls.Add(this.startupChk);
            this.Controls.Add(this.doneLabel);
            this.Controls.Add(this.installPathTxt);
            this.Controls.Add(this.installPathLabel);
            this.Controls.Add(this.createShortcut);
            this.Controls.Add(this.panel1);
            this.Controls.Add(this.closeBtn);
            this.Controls.Add(this.nextBtn);
            this.Controls.Add(this.cancelBtn);
            this.Controls.Add(this.installLog);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "Form1";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "CashRegister print helper installation wizard";
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.Form1_FormClosing);
            this.Load += new System.EventHandler(this.Form1_Load);
            this.panel1.ResumeLayout(false);
            this.panel1.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button cancelBtn;
        private System.Windows.Forms.Button nextBtn;
        private System.Windows.Forms.Button closeBtn;
        private System.Windows.Forms.Panel panel1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.CheckBox createShortcut;
        private System.Windows.Forms.TextBox installLog;
        private System.Windows.Forms.Timer logUpdater;
        private System.Windows.Forms.Label installPathLabel;
        private System.Windows.Forms.TextBox installPathTxt;
        private System.Windows.Forms.Label doneLabel;
        private System.Windows.Forms.CheckBox startupChk;
    }
}

