/*
 * Created by SharpDevelop.
 * User: StarDebris
 * Date: 6/17/2017
 * Time: 1:06 PM
 * 
 * To change this template use Tools | Options | Coding | Edit Standard Headers.
 */
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.IO;

namespace CashRegister_PrintHelper
{
	/// <summary>
	/// Description of Settings.
	/// </summary>
	public class Settings
	{
		public Settings()
		{
            printLog = new List<Log>();
		}
		
		public string defaultPrinter { get; set; }

        public List<Log> printLog { get; set; }

        public void Save()
        {
            string output = JsonConvert.SerializeObject(this);
            File.WriteAllText(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/settings.json", output);
        }
	}
}
