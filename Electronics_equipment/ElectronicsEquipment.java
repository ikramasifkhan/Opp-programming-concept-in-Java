package Electronics_equipment;

/**
 *
 * @author Radi
 */
public class ElectronicsEquipment {
   public String menufacturerName;
   public String deviceType;
   
  
    public ElectronicsEquipment(String menufacturerName, String deviceType) {
        this.menufacturerName=menufacturerName;
        this.deviceType=deviceType;    
    }
  
    public String display(){
        String deviceDetails=("Menufacturer name : " +menufacturerName+"\n Device type: "+deviceType);
        return deviceDetails;
    }
}
