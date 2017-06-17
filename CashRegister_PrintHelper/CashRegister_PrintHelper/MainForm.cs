﻿/*
 * Created by SharpDevelop.
 * User: StarDebris
 * Date: 6/17/2017
 * Time: 12:01 PM
 * 
 * To change this template use Tools | Options | Coding | Edit Standard Headers.
 */
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows.Forms;
using System.Management;
using System.Runtime.InteropServices;
using Newtonsoft.Json;
using System.IO;

namespace CashRegister_PrintHelper
{
	/// <summary>
	/// Description of MainForm.
	/// </summary>
	public partial class MainForm : Form
	{
		[DllImport("winspool.drv", 
			              CharSet = CharSet.Auto, 
			              SetLastError = true)]
	    [return: MarshalAs(UnmanagedType.Bool)]
	    public static extern Boolean SetDefaultPrinter(String name);
	   	
	    public Settings prefs = new Settings();
	    
		public MainForm()
		{
			InitializeComponent();
			
			if (File.Exists(Environment.CurrentDirectory + "/settings.json"))
				prefs = JsonConvert.DeserializeObject<Settings>(File.ReadAllText(Environment.CurrentDirectory + "/settings.json"));
			
			var printerQuery = new ManagementObjectSearcher("SELECT * from Win32_Printer");
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    
			    if (isDefault == "true" || prefs.defaultPrinter == name)
			    	comboBox1.Text = name;
	
			    comboBox1.Items.Add(name);
			}
						
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string status = printer.GetPropertyValue("Status").ToString();
			    
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    string isNetworkPrinter = printer.GetPropertyValue("Network").ToString();
			    
			    if (isDefault == "true")
			    	isDefault = "Ja";
			    else if (name == prefs.defaultPrinter)
			    	isDefault = "Ja";
			    else 
			    	isDefault = "Nee";
			    
			    if (isNetworkPrinter == "true")
			    	isNetworkPrinter = "Ja";
			    else 
			    	isNetworkPrinter = "Nee";
			    
			    if (comboBox1.Text == name)
			    {
			    	printerInfo.Text = name + "\nStatus: " + status + "\nIs standaard: " + isDefault + "\nIs netwerk printer: " + isNetworkPrinter;
			    	return;
			    }
			}
		}
		
		void AfsluitenToolStripMenuItemClick(object sender, EventArgs e)
		{
			DialogResult res = MessageBox.Show("Weet je zeker dat je de printer helper wilt sluiten?\nAls je de printer helper sluit kan je geen bonnen meer printen op de kassa!", "Weet je het zeker?", MessageBoxButtons.YesNo);
			
			if (res == DialogResult.Yes)
				Application.Exit();
		}
		
		void HideFormBtnClick(object sender, EventArgs e)
		{
			this.Hide();
		}
		
		void SaveBtnClick(object sender, EventArgs e)
		{		
			SetDefaultPrinter(comboBox1.Text);
			prefs.defaultPrinter = comboBox1.Text;
			
			string output = JsonConvert.SerializeObject(prefs);
			File.WriteAllText(Environment.CurrentDirectory + "/settings.json", output);
			
			comboBox1.Items.Clear();
			comboBox1.Text = "";
			printerInfo.Text = "";
			
			var printerQuery = new ManagementObjectSearcher("SELECT * from Win32_Printer");
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    
			    if (isDefault == "true" || prefs.defaultPrinter == name)
			    	comboBox1.Text = name;
	
			    comboBox1.Items.Add(name);
			}
						
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string status = printer.GetPropertyValue("Status").ToString();
			    
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    string isNetworkPrinter = printer.GetPropertyValue("Network").ToString();
			    
			    if (isDefault == "true")
			    	isDefault = "Ja";
			    else if (name == prefs.defaultPrinter)
			    	isDefault = "Ja";
			    else 
			    	isDefault = "Nee";
			    
			    if (isNetworkPrinter == "true")
			    	isNetworkPrinter = "Ja";
			    else 
			    	isNetworkPrinter = "Nee";
			    
			    if (comboBox1.Text == name)
			    {
			    	printerInfo.Text = name + "\nStatus: " + status + "\nIs standaard: " + isDefault + "\nIs netwerk printer: " + isNetworkPrinter;
			    	return;
			    }
			}
		}
		
		void PrintHelperTrayDoubleClick(object sender, EventArgs e)
		{
			this.Show();
		}
		
		void InstellingenToolStripMenuItemClick(object sender, EventArgs e)
		{
			this.Show();
		}
		
		void StartupTick(object sender, EventArgs e)
		{
			this.Hide();
			startup.Stop();
		}
		
		void ComboBox1SelectedIndexChanged(object sender, EventArgs e)
		{
			var printerQuery = new ManagementObjectSearcher("SELECT * from Win32_Printer");
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string status = printer.GetPropertyValue("Status").ToString();
			    
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    string isNetworkPrinter = printer.GetPropertyValue("Network").ToString();
			    
			    if (isDefault == "true")
			    	isDefault = "Ja";
			    else 
			    	isDefault = "Nee";
			    
			    if (isNetworkPrinter == "true")
			    	isNetworkPrinter = "Ja";
			    else 
			    	isNetworkPrinter = "Nee";
			    
			    if (comboBox1.Text == name)
			    {
			    	printerInfo.Text = name + "\nStatus: " + status + "\nIs standaard: " + isDefault + "\nIs netwerk printer: " + isNetworkPrinter;
			    	return;
			    }
			}
		}
		void MainFormFormClosing(object sender, FormClosingEventArgs e)
		{
			e.Cancel = true;
			this.Hide();
		}
		
		void StopBtnClick(object sender, EventArgs e)
		{
			afsluitenToolStripMenuItem.PerformClick();
		}
	}
}
