/*
 * Created by SharpDevelop.
 * User: StarDebris
 * Date: 6/17/2017
 * Time: 12:01 PM
 * 
 * To change this template use Tools | Options | Coding | Edit Standard Headers.
 */
namespace CashRegister_PrintHelper
{
	partial class MainForm
	{
		/// <summary>
		/// Designer variable used to keep track of non-visual components.
		/// </summary>
		private System.ComponentModel.IContainer components = null;
		private System.Windows.Forms.Button saveBtn;
		private System.Windows.Forms.ComboBox comboBox1;
		private System.Windows.Forms.Label label1;
		private System.Windows.Forms.Button hideFormBtn;
		private System.Windows.Forms.NotifyIcon printHelperTray;
		private System.Windows.Forms.ContextMenuStrip trayIconMenu;
		private System.Windows.Forms.ToolStripMenuItem instellingenToolStripMenuItem;
		private System.Windows.Forms.ToolStripSeparator toolStripSeparator1;
		private System.Windows.Forms.ToolStripMenuItem afsluitenToolStripMenuItem;
		private System.Windows.Forms.Timer startup;
		private System.Windows.Forms.Label printerInfo;
		private System.Windows.Forms.Button stopBtn;
		private System.Windows.Forms.Timer urlHandler;
		
		/// <summary>
		/// Disposes resources used by the form.
		/// </summary>
		/// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
		protected override void Dispose(bool disposing)
		{
			if (disposing) {
				if (components != null) {
					components.Dispose();
				}
			}
			base.Dispose(disposing);
		}
		
		/// <summary>
		/// This method is required for Windows Forms designer support.
		/// Do not change the method contents inside the source code editor. The Forms designer might
		/// not be able to load this method if it was changed manually.
		/// </summary>
		private void InitializeComponent()
		{
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(MainForm));
            this.saveBtn = new System.Windows.Forms.Button();
            this.comboBox1 = new System.Windows.Forms.ComboBox();
            this.label1 = new System.Windows.Forms.Label();
            this.hideFormBtn = new System.Windows.Forms.Button();
            this.printHelperTray = new System.Windows.Forms.NotifyIcon(this.components);
            this.trayIconMenu = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.instellingenToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.toolStripSeparator1 = new System.Windows.Forms.ToolStripSeparator();
            this.afsluitenToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.startup = new System.Windows.Forms.Timer(this.components);
            this.printerInfo = new System.Windows.Forms.Label();
            this.stopBtn = new System.Windows.Forms.Button();
            this.urlHandler = new System.Windows.Forms.Timer(this.components);
            this.activeTasks = new System.Windows.Forms.ListBox();
            this.label2 = new System.Windows.Forms.Label();
            this.logFileBtn = new System.Windows.Forms.Button();
            this.trayIconMenu.SuspendLayout();
            this.SuspendLayout();
            // 
            // saveBtn
            // 
            this.saveBtn.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.saveBtn.Location = new System.Drawing.Point(343, 214);
            this.saveBtn.Name = "saveBtn";
            this.saveBtn.Size = new System.Drawing.Size(75, 23);
            this.saveBtn.TabIndex = 0;
            this.saveBtn.Text = "Opslaan";
            this.saveBtn.UseVisualStyleBackColor = true;
            this.saveBtn.Click += new System.EventHandler(this.SaveBtnClick);
            // 
            // comboBox1
            // 
            this.comboBox1.FormattingEnabled = true;
            this.comboBox1.Location = new System.Drawing.Point(108, 18);
            this.comboBox1.Name = "comboBox1";
            this.comboBox1.Size = new System.Drawing.Size(391, 21);
            this.comboBox1.TabIndex = 1;
            this.comboBox1.SelectedIndexChanged += new System.EventHandler(this.ComboBox1SelectedIndexChanged);
            // 
            // label1
            // 
            this.label1.Location = new System.Drawing.Point(12, 21);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(90, 14);
            this.label1.TabIndex = 2;
            this.label1.Text = "Standaard printer";
            // 
            // hideFormBtn
            // 
            this.hideFormBtn.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Right)));
            this.hideFormBtn.Location = new System.Drawing.Point(424, 214);
            this.hideFormBtn.Name = "hideFormBtn";
            this.hideFormBtn.Size = new System.Drawing.Size(75, 23);
            this.hideFormBtn.TabIndex = 3;
            this.hideFormBtn.Text = "Verbergen";
            this.hideFormBtn.UseVisualStyleBackColor = true;
            this.hideFormBtn.Click += new System.EventHandler(this.HideFormBtnClick);
            // 
            // printHelperTray
            // 
            this.printHelperTray.ContextMenuStrip = this.trayIconMenu;
            this.printHelperTray.Icon = ((System.Drawing.Icon)(resources.GetObject("printHelperTray.Icon")));
            this.printHelperTray.Text = "Kassa printer helper";
            this.printHelperTray.Visible = true;
            this.printHelperTray.DoubleClick += new System.EventHandler(this.PrintHelperTrayDoubleClick);
            // 
            // trayIconMenu
            // 
            this.trayIconMenu.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.instellingenToolStripMenuItem,
            this.toolStripSeparator1,
            this.afsluitenToolStripMenuItem});
            this.trayIconMenu.Name = "trayIconMenu";
            this.trayIconMenu.Size = new System.Drawing.Size(136, 54);
            // 
            // instellingenToolStripMenuItem
            // 
            this.instellingenToolStripMenuItem.Name = "instellingenToolStripMenuItem";
            this.instellingenToolStripMenuItem.Size = new System.Drawing.Size(135, 22);
            this.instellingenToolStripMenuItem.Text = "Instellingen";
            this.instellingenToolStripMenuItem.Click += new System.EventHandler(this.InstellingenToolStripMenuItemClick);
            // 
            // toolStripSeparator1
            // 
            this.toolStripSeparator1.Name = "toolStripSeparator1";
            this.toolStripSeparator1.Size = new System.Drawing.Size(132, 6);
            // 
            // afsluitenToolStripMenuItem
            // 
            this.afsluitenToolStripMenuItem.Name = "afsluitenToolStripMenuItem";
            this.afsluitenToolStripMenuItem.Size = new System.Drawing.Size(135, 22);
            this.afsluitenToolStripMenuItem.Text = "Afsluiten";
            this.afsluitenToolStripMenuItem.Click += new System.EventHandler(this.AfsluitenToolStripMenuItemClick);
            // 
            // startup
            // 
            this.startup.Enabled = true;
            this.startup.Interval = 1000;
            this.startup.Tick += new System.EventHandler(this.StartupTick);
            // 
            // printerInfo
            // 
            this.printerInfo.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.printerInfo.Location = new System.Drawing.Point(12, 42);
            this.printerInfo.Name = "printerInfo";
            this.printerInfo.Size = new System.Drawing.Size(487, 56);
            this.printerInfo.TabIndex = 4;
            // 
            // stopBtn
            // 
            this.stopBtn.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.stopBtn.Location = new System.Drawing.Point(12, 214);
            this.stopBtn.Name = "stopBtn";
            this.stopBtn.Size = new System.Drawing.Size(75, 23);
            this.stopBtn.TabIndex = 5;
            this.stopBtn.Text = "Afsluiten";
            this.stopBtn.UseVisualStyleBackColor = true;
            this.stopBtn.Click += new System.EventHandler(this.StopBtnClick);
            // 
            // urlHandler
            // 
            this.urlHandler.Enabled = true;
            this.urlHandler.Interval = 500;
            this.urlHandler.Tick += new System.EventHandler(this.UrlHandlerTick);
            // 
            // activeTasks
            // 
            this.activeTasks.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.activeTasks.FormattingEnabled = true;
            this.activeTasks.Location = new System.Drawing.Point(15, 114);
            this.activeTasks.Name = "activeTasks";
            this.activeTasks.Size = new System.Drawing.Size(484, 95);
            this.activeTasks.TabIndex = 6;
            // 
            // label2
            // 
            this.label2.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left)));
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(12, 98);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(76, 13);
            this.label2.TabIndex = 7;
            this.label2.Text = "Actieve taken:";
            // 
            // logFileBtn
            // 
            this.logFileBtn.Location = new System.Drawing.Point(262, 215);
            this.logFileBtn.Name = "logFileBtn";
            this.logFileBtn.Size = new System.Drawing.Size(75, 23);
            this.logFileBtn.TabIndex = 8;
            this.logFileBtn.Text = "Log Bestand";
            this.logFileBtn.UseVisualStyleBackColor = true;
            this.logFileBtn.Click += new System.EventHandler(this.button1_Click);
            // 
            // MainForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(511, 249);
            this.Controls.Add(this.logFileBtn);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.activeTasks);
            this.Controls.Add(this.stopBtn);
            this.Controls.Add(this.printerInfo);
            this.Controls.Add(this.hideFormBtn);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.comboBox1);
            this.Controls.Add(this.saveBtn);
            this.Name = "MainForm";
            this.Text = "Kassa print helper";
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.MainFormFormClosing);
            this.Load += new System.EventHandler(this.MainForm_Load);
            this.trayIconMenu.ResumeLayout(false);
            this.ResumeLayout(false);
            this.PerformLayout();

		}

        private System.Windows.Forms.ListBox activeTasks;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Button logFileBtn;
    }
}
